<?php
/**
 *
 * @package PostsMerging
 * @copyright (c) 2014 Ruslan Uzdenov (rxu)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace rxu\PostsMerging\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	protected $template;
	protected $user;
	protected $auth;
	protected $db;
	protected $config;
	protected $phpbb_root_path;
	protected $php_ext;
	protected $merge;

	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\notification\manager $notification_manager, \phpbb\event\dispatcher_interface $phpbb_dispatcher, $phpbb_root_path, $php_ext)
	{
		$this->template = $template;
		$this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
		$this->config = $config;
		$this->notification_manager = $notification_manager;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->merge = true;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.modify_submit_post_data'			=> 'posts_merging',
			'core.viewtopic_post_rowset_data'		=> 'modify_viewtopic_rowset',
			'core.viewtopic_modify_post_row'		=> 'modify_viewtopic_postrow',
		);
	}

	public function posts_merging($event)
	{
		$mode = $event['mode'];
		$subject = $event['subject'];
		$username = $event['username'];
		$topic_type = $event['topic_type'];
		$poll = $event['poll'];
		$data = $event['data'];
		$update_message = $event['update_message'];
		$update_search_index = $event['update_search_index'];

		$post_need_approval = ((!$this->auth->acl_get('f_noapprove', $data['forum_id']) && empty($data['force_approved_state'])) || (isset($data['force_approved_state']) && !$data['force_approved_state'])) ? true : false;

		if (!$post_need_approval && ($event['mode'] == 'reply' || $event['mode'] == 'quote') && $this->config['merge_interval'] > 0 && !in_array($data['forum_id'], explode(",", $this->config['merge_no_forums'])) && !in_array($data['topic_id'], explode(",", $this->config['merge_no_topics'])))
		{
			$sql = 'SELECT f.*, t.*, p.* FROM ' . POSTS_TABLE . ' p, ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . ' f 
				WHERE p.post_id = t.topic_last_post_id
					AND t.topic_id = ' . (int) $data['topic_id'] . ' 
					AND (f.forum_id = t.forum_id
							OR f.forum_id = ' . (int) $data['forum_id'] . ')';

			$result = $this->db->sql_query($sql);
			$merge_post_data = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			$merge_post_id = $merge_post_data['post_id'];

			if (!$merge_post_id)
			{
				$this->user->setup('posting');
				trigger_error('NO_POST');
			}

			$data['post_id'] = $merge_post_id;

			$merge_interval = intval($this->config['merge_interval']) * 3600;
			$current_time = time();

			// Do not merge if the post is locked or if allowed merge period has left
			if ($merge_post_data['post_edit_locked'] || (($current_time - $merge_post_data['topic_last_post_time']) > $merge_interval))
			{
				$this->merge = false;
				return;
			}

			// Everything seems to be ok, do merging
			if ($this->merge && $merge_post_data['poster_id'] == $this->user->data['user_id'] && $this->user->data['is_registered'] && $this->user->data['user_id'] != ANONYMOUS)
			{
				$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');

				// Create message parser instance
				include_once($phpbb_root_path . 'includes/message_parser.' . $this->php_ext);
				$message_parser = new \parse_message();

				// Handle old message
				$message_parser->message = &$merge_post_data['post_text'];
				unset($merge_post_data['post_text']);

				// Decode old message text for update properly
				$message_parser->decode_message($merge_post_data['bbcode_uid']);
				$merge_post_data['post_text'] = html_entity_decode($message_parser->message,  ENT_NOQUOTES, 'UTF-8');

				// Handle addon
				$message_parser->message = &$data['message'];
				unset($data['message']);

				// Decode addon message text for update properly
				$message_parser->decode_message($data['bbcode_uid']);
				$data['message'] = html_entity_decode($message_parser->message,  ENT_NOQUOTES, 'UTF-8');

				unset($message_parser);

				//Handle with inline attachments
				if (sizeof($data['attachment_data']))
				{
					for($i = 0; $i < sizeof($data['attachment_data']); $i++)
					{
						$merge_post_data['post_text'] = preg_replace('#\[attachment=([0-9]+)\](.*?)\[\/attachment\]#e', "'[attachment='.(\\1 + 1).']\\2[/attachment]'", $merge_post_data['post_text']);
					}
				}

				// Prepare message separator
				$datetime_new = date_create('@' . (string) $current_time);
				$datetime_old = date_create('@' . (string) $merge_post_data['post_time']);
				$interval = date_diff($datetime_new, $datetime_old);

				$time[] = ($interval->h) ? $this->user->lang('D_HOURS', $interval->h) : null;
				$time[] = ($interval->i) ? $this->user->lang('D_MINUTES', $interval->i) : null;
				$time[] = ($interval->s) ? $this->user->lang('D_SECONDS', $interval->s) : null;
				$separator = $this->user->lang('MERGE_SEPARATOR', implode(' ', $time));

				// Merge subject
				if (!empty($subject) && $subject != $merge_post_data['post_subject'] && $merge_post_data['post_id'] != $merge_post_data['topic_first_post_id'])
				{
					$separator .= sprintf($this->user->lang['MERGE_SUBJECT'], $subject);
				}
				$options = '';

				// Merge posts
				$merge_post_data['post_text'] = $merge_post_data['post_text'] . $separator . $data['message'];

				//Prepare post for submit
				generate_text_for_storage($merge_post_data['post_text'], $merge_post_data['bbcode_uid'], $merge_post_data['bbcode_bitfield'], $options, $merge_post_data['enable_bbcode'], $merge_post_data['enable_magic_url'], $merge_post_data['enable_smilies']);

				$poster_id = (int) $merge_post_data['poster_id'];
				$post_time = $current_time;

				// Prepare post data for update
				$sql_data[POSTS_TABLE]['sql'] = array(
					'bbcode_uid'		=> $merge_post_data['bbcode_uid'],
					'bbcode_bitfield'	=> $merge_post_data['bbcode_bitfield'],
					'post_text'			=> $merge_post_data['post_text'],
					'post_checksum'		=> md5($merge_post_data['post_text']),
					'post_created'		=> ($merge_post_data['post_created']) ? $merge_post_data['post_created'] : $merge_post_data['post_time'],
					'post_time'			=> $post_time,
					'post_attachment'	=> (!empty($data['attachment_data'])) ? 1 : ($merge_post_data['post_attachment'] ? 1 : 0),
				);

				$sql_data[TOPICS_TABLE]['sql'] = array(
					'topic_last_post_id'		=> $merge_post_id,
					'topic_last_poster_id'		=> $poster_id,
					'topic_last_poster_name'	=> (!$this->user->data['is_registered'] && $event['username']) ? $event['username'] : (($this->user->data['user_id'] != ANONYMOUS) ? $this->user->data['username'] : ''),
					'topic_last_poster_colour'	=> ($this->user->data['user_id'] != ANONYMOUS) ? $this->user->data['user_colour'] : '',
					'topic_last_post_subject'	=> utf8_normalize_nfc($merge_post_data['post_subject']),
					'topic_last_post_time'		=> $post_time,
					'topic_attachment'			=> (!empty($data['attachment_data']) || (isset($merge_post_data['topic_attachment']) && $merge_post_data['topic_attachment'])) ? 1 : 0,
				);

				$sql_data[FORUMS_TABLE]['sql'] = array(
					'forum_last_post_id'		=> $merge_post_id,
					'forum_last_post_subject'	=> utf8_normalize_nfc($merge_post_data['post_subject']),
					'forum_last_post_time'		=> $post_time,
					'forum_last_poster_id'		=> $poster_id,
					'forum_last_poster_name'	=> (!$this->user->data['is_registered'] && $event['username']) ? $event['username'] : (($this->user->data['user_id'] != ANONYMOUS) ? $this->user->data['username'] : ''),
					'forum_last_poster_colour'	=> ($this->user->data['user_id'] != ANONYMOUS) ? $this->user->data['user_colour'] : '',
				);

				// Update post information - submit merged post
				$sql = 'UPDATE ' . POSTS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_data[POSTS_TABLE]['sql']) . " WHERE post_id = $merge_post_id";
				$this->db->sql_query($sql);

				$sql = 'UPDATE ' . TOPICS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_data[TOPICS_TABLE]['sql']) . ' WHERE topic_id = ' . $data['topic_id'];
				$this->db->sql_query($sql);

				$sql = 'UPDATE ' . USERS_TABLE . "	SET user_lastpost_time = $post_time	WHERE user_id = " . (int) $this->user->data['user_id'];
				$this->db->sql_query($sql);

				$sql = 'UPDATE ' . FORUMS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_data[FORUMS_TABLE]['sql']) . ' WHERE forum_id = ' . $data['forum_id'];
				$this->db->sql_query($sql);

				// Submit Attachments
				if (!empty($data['attachment_data']))
				{
					$space_taken = $files_added = 0;
					$orphan_rows = array();

					foreach ($data['attachment_data'] as $pos => $attach_row)
					{
						$orphan_rows[(int) $attach_row['attach_id']] = array();
					}

					if (sizeof($orphan_rows))
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

					foreach ($data['attachment_data'] as $pos => $attach_row)
					{
						if ($attach_row['is_orphan'] && !in_array($attach_row['attach_id'], array_keys($orphan_rows)))
						{
							continue;
						}

						if (!$attach_row['is_orphan'])
						{
							// update entry in db if attachment already stored in db and filespace
							$sql = 'UPDATE ' . ATTACHMENTS_TABLE . "
								SET attach_comment = '" . $this->db->sql_escape($attach_row['attach_comment']) . "'
								WHERE attach_id = " . (int) $attach_row['attach_id'] . '
									AND is_orphan = 0';
							$this->db->sql_query($sql);
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

							$attach_sql = array(
								'post_msg_id'		=> $merge_post_id,
								'topic_id'			=> $data['topic_id'],
								'is_orphan'			=> 0,
								'poster_id'			=> $poster_id,
								'attach_comment'	=> $attach_row['attach_comment'],
							);

							$sql = 'UPDATE ' . ATTACHMENTS_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $attach_sql) . '
								WHERE attach_id = ' . $attach_row['attach_id'] . '
									AND is_orphan = 1
									AND poster_id = ' . (int) $this->user->data['user_id'];
							$this->db->sql_query($sql);
						}
					}

					if ($space_taken && $files_added)
					{
						$this->config->set('upload_dir_size', $this->config['upload_dir_size'] + $space_taken, true);
						$this->config->set('num_files', $this->config['num_files'] + $files_added, true);
					}
				}

				// Index message contents
				if ($merge_post_data['enable_indexing'])
				{
					// Select the search method and do some additional checks to ensure it can actually be utilised
					$search_type = $this->config['search_type'];

					if (!class_exists($search_type))
					{
						trigger_error('NO_SUCH_SEARCH_MODULE');
					}

					$error = false;
					$search = new $search_type($error, $this->phpbb_root_path, $this->php_ext, $this->auth, $this->config, $this->db, $this->user);

					if ($error)
					{
						trigger_error($error);
					}

					$search->index('edit', $merge_post_id, $merge_post_data['post_text'], $subject, $poster_id, $data['forum_id']);
				}

				// Mark the post and the topic read
				markread('post', $data['forum_id'], $data['topic_id'], $post_time);
				markread('topic', $data['forum_id'], $data['topic_id'], time());

				// Handle read tracking
				if ($this->config['load_db_lastread'] && $this->user->data['is_registered'])
				{
					$sql = 'SELECT mark_time
						FROM ' . FORUMS_TRACK_TABLE . '
						WHERE user_id = ' . (int) $this->user->data['user_id'] . '
							AND forum_id = ' . $data['forum_id'];
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
						WHERE forum_id = ' . $data['forum_id'];
					$result = $this->db->sql_query($sql);
					$forum_last_post_time = (int) $this->db->sql_fetchfield('forum_last_post_time');
					$this->db->sql_freeresult($result);

					update_forum_tracking_info($data['forum_id'], $forum_last_post_time, $f_mark_time, false);
				}

				if ($this->auth->acl_get('f_noapprove', $data['forum_id']) || $this->auth->acl_get('m_approve', $data['forum_id']))
				{
					// If a username was supplied or the poster is a guest, we will use the supplied username.
					// Doing it this way we can use "...post by guest-username..." in notifications when
					// "guest-username" is supplied or ommit the username if it is not.
					$username = ($event['username'] !== '' || !$this->user->data['is_registered']) ? $event['username'] : $this->user->data['username'];

					// Send Notifications
					$notification_data = array_merge($data, array(
						'topic_title'		=> (isset($data['topic_title'])) ? $data['topic_title'] : $subject,
						'post_username'		=> $username,
						'poster_id'			=> $poster_id,
						'post_text'			=> $data['message'],
						'post_time'			=> $current_time,
						'post_subject'		=> $subject,
					));

					switch ($mode)
					{
						case 'post':
							$this->notification_manager->add_notifications(array(
								'quote',
								'topic',
							), $notification_data);
						break;

						case 'reply':
						case 'quote':
							$this->notification_manager->add_notifications(array(
								'quote',
								'bookmark',
								'post',
							), $notification_data);
						break;
					}
				}

				//Generate redirection URL and redirecting
				$params = $add_anchor = '';
				$params .= '&amp;t=' . $data['topic_id'];
				$params .= '&amp;p=' . $merge_post_id;
				$add_anchor = '#p' . $merge_post_id;
				$url = "{$this->phpbb_root_path}viewtopic.$this->php_ext";
				$url = append_sid($url, 'f=' . $data['forum_id'] . $params) . $add_anchor;

				/**
				* Modify the data for post submitting
				*
				* @event rxu.postsmerging.posts_merging_end
				* @var	string	mode				Variable containing posting mode value
				* @var	string	subject				Variable containing post subject value
				* @var	string	username			Variable containing post author name
				* @var	int		topic_type			Variable containing topic type value
				* @var	array	poll				Array with the poll data for the post
				* @var	array	data				Array with the data for the post
				* @var	bool	update_message		Flag indicating if the post will be updated
				* @var	bool	update_search_index	Flag indicating if the search index will be updated
				* @var	string	url					The "Return to topic" URL
				* @since 2.0.0
				*/
				$vars = array(
					'mode',
					'subject',
					'username',
					'topic_type',
					'poll',
					'data',
					'update_message',
					'update_search_index',
					'url',
				);
				extract($this->phpbb_dispatcher->trigger_event('rxu.postsmerging.posts_merging_end', compact($vars)));

				meta_refresh(3, $url);

				$message = (!$this->auth->acl_get('f_noapprove', $merge_post_data['forum_id']) && !$this->auth->acl_get('m_approve', $merge_post_data['forum_id'])) ? 'POST_STORED_MOD' : 'POST_STORED';
				$message = $this->user->lang[$message] . (($this->auth->acl_get('f_noapprove', $merge_post_data['forum_id']) || $this->auth->acl_get('m_approve', $merge_post_data['forum_id'])) ? '<br /><br />' . sprintf($this->user->lang['VIEW_MESSAGE'], '<a href="' . $url . '">', '</a>') : '');
				$message .= '<br /><br />' . sprintf($this->user->lang['RETURN_FORUM'], '<a href="' . append_sid("{$this->phpbb_root_path}viewforum.$this->php_ext", 'f=' . $merge_post_data['forum_id']) . '">', '</a>');

				trigger_error($message);
			}
		}
	}

	public function modify_viewtopic_rowset($event)
	{
		$rowset = $event['rowset_data'];
		$rowset = array_merge($rowset, array('post_created'	=> $event['row']['post_created']));
		$event['rowset_data'] = $rowset;
	}

	public function modify_viewtopic_postrow($event)
	{
		$post_row = $event['post_row'];
		$post_row['POST_DATE'] = (!$event['row']['post_created']) ? $this->user->format_date($event['row']['post_time']) : $this->user->format_date($event['row']['post_created']);
		$event['post_row'] = $post_row;
	}
}
