<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\postsmerging\core;

class helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var string tables.postsmerging */
	protected $postsmerging_table;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config                 $config             Config object
	 * @param \phpbb\db\driver\driver_interface    $db                 DBAL object
	 * @param \phpbb\auth\auth                     $auth               User object
	 * @param \phpbb\user                          $user               User object
	 * @param \phpbb\event\dispatcher_interface	   $phpbb_dispatcher   Event dispatcher object
	 * @param string                               $phpbb_root_path    phpbb_root_path
	 * @param string                               $php_ext            phpEx
	 * @param string                               $postsmerging_table tables.postsmerging
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\auth\auth $auth, \phpbb\user $user,
		\phpbb\event\dispatcher_interface $phpbb_dispatcher,
		$phpbb_root_path,
		$php_ext,
		$postsmerging_table
	)
	{
		$this->config = $config;
		$this->db = $db;
		$this->auth = $auth;
		$this->user = $user;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->postsmerging_table = $postsmerging_table;
	}

	/**
	 * Get forum/topic ids where posts merging is disabled
	 *
	 * @param array	$data	Array with forum & topic ids
	 *
	 * @return bool
	 */
	public function excluded_from_merge($data)
	{
		return (in_array($data['forum_id'], explode(',', $this->config['merge_no_forums']))
			|| in_array($data['topic_id'], explode(',', $this->config['merge_no_topics'])));
	}

	/**
	 * Check whether the post shall be put to moderaton queue
	 *
	 * @param array	$data	Post data array
	 *
	 * @return bool
	 */
	public function post_needs_approval($data)
	{
		return ((!$this->auth->acl_get('f_noapprove', (int) $data['forum_id'])
			&& empty($data['force_approved_state'])) || (isset($data['force_approved_state'])
			&& !$data['force_approved_state']));
	}

	/**
	 * Get topic last post data
	 *
	 * @param array	$data	Post data array
	 *
	 * @return array $last_post_data Array with the last topic post data
	 */
	public function get_last_post_data($data)
	{
		$forum_id = (int) $data['forum_id'];
		$topic_id = (int) $data['topic_id'];
		$user_id = (int) $this->user->data['user_id'];
		$sql_array = [
			'SELECT' => 'f.enable_indexing, f.forum_id, p.bbcode_bitfield, p.bbcode_uid, pm.post_created,
				p.enable_bbcode,  p.enable_magic_url, p.enable_smilies, p.poster_id, p.post_attachment,
				p.post_edit_locked, p.post_id, p.post_subject, p.post_text, p.post_time, p.post_visibility, t.topic_attachment,
				t.topic_first_post_id, t.topic_id, t.topic_last_post_time',
			'FROM' => [FORUMS_TABLE => 'f', POSTS_TABLE => 'p', TOPICS_TABLE => 't'],
			'LEFT_JOIN'	=> [
				[
					'FROM'	=> [$this->postsmerging_table => 'pm'],
					'ON'	=> 'p.post_id = pm.post_id',
				],
			],
			'WHERE' => "p.post_id = t.topic_last_post_id
				AND t.topic_posts_unapproved = 0
				AND t.topic_id = $topic_id
				AND (f.forum_id = t.forum_id 
					OR f.forum_id = $forum_id)",
		];

		$sql = $this->db->sql_build_query('SELECT', $sql_array);
		$result = $this->db->sql_query_limit($sql, 1);
		$last_post_data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $last_post_data;
	}

	/**
	 * Submit attachments to database
	 *
	 * @param array	$data	Post data array
	 *
	 * @return void
	 */
	public function submit_attachments($data)
	{
		if (empty($data['attachment_data']))
		{
			return;
		}

		$space_taken = $files_added = 0;
		$orphan_rows = [];

		foreach ($data['attachment_data'] as $pos => $attach_row)
		{
			$orphan_rows[(int) $attach_row['attach_id']] = [];
		}

		if (count($orphan_rows))
		{
			$sql = 'SELECT attach_id, filesize, physical_filename
				FROM ' . ATTACHMENTS_TABLE . '
				WHERE ' . $this->db->sql_in_set('attach_id', array_keys($orphan_rows)) . '
					AND is_orphan = 1
					AND poster_id = ' . (int) $this->user->data['user_id'];
			$result = $this->db->sql_query($sql);

			$orphan_rows = array();
			while ($row = $this->db->sql_fetchrow($result))
			{
				$orphan_rows[$row['attach_id']] = $row;
			}
			$this->db->sql_freeresult($result);
		}

		foreach ($data['attachment_data'] as $attach_row)
		{
			if ($attach_row['is_orphan'] && !in_array($attach_row['attach_id'], array_keys($orphan_rows)))
			{
				continue;
			}

			if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $attach_row['attach_comment']))
			{
				trigger_error('ATTACH_COMMENT_NO_EMOJIS');
			}

			if (!$attach_row['is_orphan'])
			{
				// update entry in db if attachment already stored in db and filespace
				$sql = 'UPDATE ' . ATTACHMENTS_TABLE . "
					SET attach_comment = '" . $this->db->sql_escape($attach_row['attach_comment']) . "'
					WHERE attach_id = " . (int) $attach_row['attach_id'] . '
						AND is_orphan = 0';
			}
			else
			{
				// insert attachment into db
				if (!@file_exists($this->phpbb_root_path . $this->config['upload_path'] . '/' . basename($orphan_rows[$attach_row['attach_id']]['physical_filename'])))
				{
					continue;
				}

				$space_taken += $orphan_rows[$attach_row['attach_id']]['filesize'];
				$files_added++;

				$attach_sql = [
					'post_msg_id'		=> (int) $data['post_id'],
					'topic_id'			=> (int) $data['topic_id'],
					'is_orphan'			=> 0,
					'poster_id'			=> (int) $this->user->data['user_id'],
					'attach_comment'	=> $attach_row['attach_comment'],
				];

				$sql = 'UPDATE ' . ATTACHMENTS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $attach_sql) . '
					WHERE attach_id = ' . (int) $attach_row['attach_id'] . '
						AND is_orphan = 1
						AND poster_id = ' . (int) $this->user->data['user_id'];
			}

			$this->db->sql_query($sql);
		}

		if ($space_taken && $files_added)
		{
			$this->config->set('upload_dir_size', $this->config['upload_dir_size'] + $space_taken, true);
			$this->config->set('num_files', $this->config['num_files'] + $files_added, true);
		}
	}

	/**
	 * Decode post text
	 *
	 * @param array	$data	Post data array
	 *
	 * @return string $text Decoded post text
	 */
	public function prepare_text_for_merge($data)
	{
		// Create message parser instance
		include_once($this->phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
		$message_parser = new \parse_message();
		$text = (isset($data['post_text'])) ? $data['post_text'] : $data['message'];
		$message_parser->message = $text;

		// Decode message text properly
		$message_parser->decode_message($data['bbcode_uid']);
		$text = html_entity_decode($message_parser->message, ENT_COMPAT, 'UTF-8');

		return $text;
	}

	/**
	 * Get time interval
	 *
	 * @param int	$old_time	Unix timestamp
	 * @param int	$new_time	Unix timestamp
	 *
	 * @return \DateInterval $interval Dateinterval object
	 */
	public function get_time_interval($old_time, $new_time)
	{
		$datetime_new = date_create('@' . $old_time);
		$datetime_old = date_create('@' . $new_time);
		$interval = date_diff($datetime_new, $datetime_old);

		return $interval;
	}

	/**
	 * Get Post attachments count
	 *
	 * @param int	$post_id	The post ID
	 *
	 * @return int $num_attachments Post attachments count
	 */
	public function count_post_attachments($post_id)
	{
		$sql = 'SELECT COUNT(*) as num_attachments
			FROM ' . ATTACHMENTS_TABLE . "
			WHERE post_msg_id = $post_id
				AND in_message = 0
			GROUP BY post_msg_id";
		$result = $this->db->sql_query($sql);
		$num_attachments = (int) $this->db->sql_fetchfield('num_attachments');

		return ($num_attachments) ?: 0;
	}

	/**
	 * Update read tracking information
	 *
	 * @param array	$data	Post data array
	 *
	 * @return void
	 */
	public function update_read_tracking($data)
	{
		// Mark the post and the topic read
		markread('post', (int) $data['forum_id'], (int) $data['topic_id'], $data['post_time']);
		markread('topic', (int) $data['forum_id'], (int) $data['topic_id'], time());

		// Handle read tracking
		if ($this->config['load_db_lastread'] && $this->user->data['is_registered'])
		{
			$sql = 'SELECT mark_time
				FROM ' . FORUMS_TRACK_TABLE . '
				WHERE user_id = ' . (int) $this->user->data['user_id'] . '
					AND forum_id = ' . (int) $data['forum_id'];
			$result = $this->db->sql_query($sql);
			$f_mark_time = (int) $this->db->sql_fetchfield('mark_time');
			$this->db->sql_freeresult($result);
		}
		else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
		{
			$f_mark_time = false;
		}

		if (($this->config['load_db_lastread'] && $this->user->data['is_registered']) || $this->config['load_anon_lastread'] || $this->user->data['is_registered'])
		{
			// Update forum info
			$sql = 'SELECT forum_last_post_time
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . (int) $data['forum_id'];
			$result = $this->db->sql_query($sql);
			$forum_last_post_time = (int) $this->db->sql_fetchfield('forum_last_post_time');
			$this->db->sql_freeresult($result);

			update_forum_tracking_info((int) $data['forum_id'], $forum_last_post_time, $f_mark_time, false);
		}
	}

	/**
	 * Update search index
	 *
	 * @param array	$data	Post data array
	 *
	 * @return void
	 */
	public function update_search_index($data)
	{
		if ($data['enable_indexing'])
		{
			// Select the search method and do some additional checks to ensure it can actually be utilised
			$search_type = $this->config['search_type'];

			if (!class_exists($search_type))
			{
				trigger_error('NO_SUCH_SEARCH_MODULE');
			}

			$error = false;
			$search = new $search_type($error, $this->phpbb_root_path, $this->php_ext, $this->auth, $this->config, $this->db, $this->user, $this->phpbb_dispatcher);

			if ($error)
			{
				trigger_error($error);
			}

			$search->index('edit', (int) $data['post_id'], $data['post_text'], $data['post_subject'], (int) $data['poster_id'], (int) $data['forum_id']);
		}
	}

	/**
	 * Submit merged post to the database
	 *
	 * @param array	$data	Post data array
	 *
	 * @return void
	 */
	public function submit_post_to_database($data)
	{
		// Prepare post data for update
		$sql_data[POSTS_TABLE]['sql'] = [
			'bbcode_uid'		=> substr(base_convert(unique_id(), 16, 36), 0, BBCODE_UID_LEN),
			'bbcode_bitfield'	=> $data['bbcode_bitfield'],
			'post_text'			=> $data['post_text'],
			'post_checksum'		=> md5($data['post_text']),
			'post_time'			=> $data['post_time'],
			'post_attachment'	=> $data['post_attachment'],
		];

		$sql_data[TOPICS_TABLE]['sql'] = [
			'topic_last_post_time'		=> $data['post_time'],
			'topic_attachment'			=> ($data['post_attachment'] || $data['topic_attachment']) ? 1 : 0,
		];

		$sql_data[FORUMS_TABLE]['sql'] = [
			'forum_last_post_time'		=> $data['post_time'],
			'forum_last_post_id'		=> $data['post_id'],
			'forum_last_post_subject'	=> $data['post_subject'],
			'forum_last_poster_id'		=> (int) $this->user->data['user_id'],
			'forum_last_poster_name'	=> $this->user->data['username'],
			'forum_last_poster_colour'	=> $this->user->data['user_colour'],
		];

		$sql_data[USERS_TABLE]['sql'] = [
			'user_lastpost_time'		=> $data['post_time'],
		];

		$sql_data[$this->postsmerging_table]['sql'] = [
			'post_id'		=> $data['post_id'],
			'post_created'	=> $data['original_post_time'],
		];

		// Update post information - submit merged post
		$sql = 'UPDATE ' . POSTS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_data[POSTS_TABLE]['sql']) . ' WHERE post_id = ' . (int) $data['post_id'];
		$this->db->sql_query($sql);

		$sql = 'UPDATE ' . TOPICS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_data[TOPICS_TABLE]['sql']) . ' WHERE topic_id = ' . (int) $data['topic_id'];
		$this->db->sql_query($sql);

		$sql = 'UPDATE ' . USERS_TABLE . '	SET ' . $this->db->sql_build_array('UPDATE', $sql_data[USERS_TABLE]['sql']) . ' WHERE user_id = ' . (int) $this->user->data['user_id'];
		$this->db->sql_query($sql);

		$sql = 'UPDATE ' . FORUMS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_data[FORUMS_TABLE]['sql']) . ' WHERE forum_id = ' . (int) $data['forum_id'];
		$this->db->sql_query($sql);

		// Only insert a row with post_created on first post merging to keep original post time
		if (is_null($data['post_created']))
		{
			$sql = 'INSERT INTO ' . $this->postsmerging_table . ' ' . $this->db->sql_build_array('INSERT', $sql_data[$this->postsmerging_table]['sql']);
			$this->db->sql_query($sql);
		}
	}
}
