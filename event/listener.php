<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\postsmerging\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \rxu\postsmerging\core\helper */
	protected $helper;

	/** @var string tables.postsmerging */
	protected $postsmerging_table;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var \phpbb\request\type_cast_helper_interface type_cast_helper */
	protected $type_cast_helper;

	/** @var int merge_interval */
	protected $merge_interval;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth                          $auth                  Auth object
	 * @param \phpbb\config\config                      $config                Config object
	 * @param \phpbb\config\db_text                     $config_text           Config_text object
	 * @param \phpbb\event\dispatcher_interface         $phpbb_dispatcher      Event dispatcher object
	 * @param \phpbb\notification\manager               $notification_manager  Notification manager object
	 * @param \phpbb\language\language                  $language              Language object
	 * @param \phpbb\request\request_interface          $request               Request object
	 * @param \phpbb\template\template                  $template              Template object
	 * @param \phpbb\user                               $user                  User object
	 * @param \rxu\postsmerging\core\helper             $helper                The extension helper object
	 * @param string                                    $postsmerging_table    tables.postsmerging
	 * @param string                                    $phpbb_root_path       phpbb_root_path
	 * @param string                                    $php_ext               phpEx
	 * @param \phpbb\request\type_cast_helper_interface $type_cast_helper      The type cast helper object
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\config\config $config,
		\phpbb\config\db_text $config_text,
		\phpbb\event\dispatcher_interface $phpbb_dispatcher,
		\phpbb\notification\manager $notification_manager,
		\phpbb\language\language $language,
		\phpbb\request\request_interface $request,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\rxu\postsmerging\core\helper $helper,
		$postsmerging_table,
		$phpbb_root_path,
		$php_ext,
		\phpbb\request\type_cast_helper_interface $type_cast_helper = null
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->notification_manager = $notification_manager;
		$this->language = $language;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->postsmerging_table = $postsmerging_table;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		if ($type_cast_helper)
		{
			$this->type_cast_helper = $type_cast_helper;
		}
		else
		{
			$this->type_cast_helper = new \phpbb\request\type_cast_helper();
		}

		$this->merge_interval = intval($this->config['merge_interval']) * 3600;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.display_forums_modify_sql'			=> 'modify_sql',
			'core.display_forums_modify_template_vars'	=> 'modify_topic_last_post_time',
			'core.modify_submit_post_data'				=> ['posts_merging', -5],
			'core.permissions'							=> 'add_permission',
			'core.posting_modify_template_vars'			=> 'get_posts_merging_option',
			'core.viewforum_get_topic_data'				=> 'modify_sql',
			'core.viewforum_modify_topicrow'			=> 'modify_topic_last_post_time',
			'core.viewtopic_get_post_data'				=> 'modify_sql',
			'core.viewtopic_modify_post_row'			=> 'modify_viewtopic_postrow',
			'core.viewtopic_modify_page_title'			=> 'get_posts_merging_option',
			'core.viewtopic_post_rowset_data'			=> 'modify_viewtopic_rowset',
		];
	}

	/**
	 * Merge posts main procedure
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
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

		$current_time = time();

		// Preliminary checks if the post-based post merging option was checked,
		// and user has permission for merging or ignoring merging
		$do_not_merge_with_previous = $this->request->is_set_post('posts_merging_option')
			&& $this->auth->acl_get('u_postsmerging') && $this->auth->acl_get('u_postsmerging_ignore');

		if ($this->auth->acl_get('u_postsmerging') && !$do_not_merge_with_previous && !$this->helper->post_needs_approval($data)
			&& in_array($mode, ['reply', 'quote']) && $this->merge_interval
			&& !$this->helper->excluded_from_merge($data)
		)
		{
			$merge_post_data = $this->helper->get_last_post_data($data);
			$post_visibility = $merge_post_data['post_visibility'];

			// Do not merge if there's no last post data, the poster is not current user, user is not registered,or
			// the post is locked, has not yet been approved or allowed merge period has left
			if (!$merge_post_data || ($merge_post_data['poster_id'] != $this->user->data['user_id']) || $merge_post_data['post_edit_locked'] ||
				(int) $merge_post_data['post_visibility'] == ITEM_UNAPPROVED ||
				(($current_time - (int) $merge_post_data['topic_last_post_time']) > $this->merge_interval) ||
				!$this->user->data['is_registered']
			)
			{
				return;
			}

			// Also, don't let user to violate attachments limit by posts merging
			// In this case, also don't merge posts and return
			// Exceptions are administrators and forum moderators
			$num_old_attachments = $this->helper->count_post_attachments((int) $merge_post_data['post_id']);
			$num_new_attachments = count($data['attachment_data']);
			$total_attachments_count = $num_old_attachments + $num_new_attachments;
			if (($total_attachments_count > $this->config['max_attachments']) && !$this->auth->acl_get('a_')
				&& !$this->auth->acl_get('m_', (int) $data['forum_id'])
			)
			{
				return;
			}

			$data['post_id'] = (int) $merge_post_data['post_id'];
			$merge_post_data['post_attachment'] = ($total_attachments_count) ? 1 : 0;

			// Decode old message and addon
			$merge_post_data['post_text'] = $this->helper->prepare_text_for_merge($merge_post_data);
			$data['message'] = $this->helper->prepare_text_for_merge($data);

			// Handle inline attachments BBCode in old message
			if ($num_new_attachments)
			{
				$merge_post_data['post_text'] = preg_replace_callback(
					'#\[attachment=([0-9]+)\](.*?)\[\/attachment\]#',
					function ($match) use ($num_new_attachments)
					{
						return '[attachment=' . ($match[1] + $num_new_attachments) . ']' . $match[2] . '[/attachment]';
					},
					$merge_post_data['post_text']
				);
			}

			// Prepare message separator
			$separator = (string) $this->config_text->get('posts_merging_separator_text');
			$this->language->add_lang('posts_merging', 'rxu/postsmerging');

			// Calculate the time interval
			$interval = $this->helper->get_time_interval($current_time, $merge_post_data['post_time']);
			$time = array();
			$time[] = ($interval->y) ? $this->language->lang('D_YEAR', $interval->y) : null;
			$time[] = ($interval->m) ? $this->language->lang('D_MON', $interval->m) : null;
			$time[] = ($interval->d) ? $this->language->lang('D_MDAY', $interval->d) : null;
			$time[] = ($interval->h) ? $this->language->lang('D_HOURS', $interval->h) : null;
			$time[] = ($interval->i) ? $this->language->lang('D_MINUTES', $interval->i) : null;
			$time[] = ($interval->s) ? $this->language->lang('D_SECONDS', $interval->s) : null;

			// Translate separator text
			$separator = preg_replace_callback(
				'/{L_([A-Z0-9_]+)}/',
				function ($matches)
				{
					return $this->language->lang($matches[1]);
				},
				$separator
			);

			// Eval linefeeds and generate the separator, time interval included
			$separator = sprintf(str_replace('\n', "\n", $separator), implode(' ', $time));

			// Merge subject
			if (!empty($subject) && $subject != $merge_post_data['post_subject'] && $merge_post_data['post_id'] != $merge_post_data['topic_first_post_id'])
			{
				$separator .= $this->language->lang('MERGE_SUBJECT', $subject);
			}

			// Merge posts
			$merge_post_data['post_text'] = $merge_post_data['post_text'] . $separator . $data['message'];

			// Make sure the message is safe
			$this->type_cast_helper->recursive_set_var($merge_post_data['post_text'], '', true);

			//Prepare post for submit
			$options = '';
			$warn_msg = generate_text_for_storage($merge_post_data['post_text'], $merge_post_data['bbcode_uid'], $merge_post_data['bbcode_bitfield'], $options, $merge_post_data['enable_bbcode'], $merge_post_data['enable_magic_url'], $merge_post_data['enable_smilies']);

			// If $warn_msg is not empty, the merged message does not conform some restrictions
			// In this case we simply don't merge and return back to the function submit_post()
			if (!empty($warn_msg))
			{
				return;
			}

			// Keep original post time to save it later within the post_created field
			// Update post time with the current time and submit post to the database
			$merge_post_data['original_post_time'] = $merge_post_data['post_time'];
			$merge_post_data['post_time'] = $data['post_time'] = $current_time;
			$this->helper->submit_post_to_database($merge_post_data);

			// Submit attachments
			$this->helper->submit_attachments($data);

			// Update read tracking
			$this->helper->update_read_tracking($data);

			// If a username was supplied or the poster is a guest, we will use the supplied username.
			// Doing it this way we can use "...post by guest-username..." in notifications when
			// "guest-username" is supplied or ommit the username if it is not.
			$username = ($username !== '' || !$this->user->data['is_registered']) ? $username : $this->user->data['username'];

			// Send Notifications
			// Despite the post_id is the same and users who've been already notified
			// won't be notified again about the same post_id, we send notifications
			// for new users possibly subscribed to it
			$notification_data = array_merge($merge_post_data, [
				'topic_title'	=> (isset($merge_post_data['topic_title'])) ? $merge_post_data['topic_title'] : $subject,
				'post_username'	=> $username,
				'post_subject'	=> $subject,
			]);

			$data_ary = $data;
			$poster_id = ($mode == 'edit') ? $data_ary['poster_id'] : (int) $this->user->data['user_id'];
			/**
			 * This event allows you to modify the notification data upon submission
			 *
			 * @event rxu.postsmerging.modify_submit_notification_data
			 * @var	array	notification_data	The notification data to be inserted in to the database
			 * @var	array	data_ary			The data array with a lot of the post submission data
			 * @var string	mode				The posting mode
			 * @var int		poster_id			Poster id
			 * @since 3.0.0
			 */
			$vars = [
				'notification_data',
				'data_ary',
				'mode',
				'poster_id',
			];
			extract($this->phpbb_dispatcher->trigger_event('rxu.postsmerging.modify_submit_notification_data', compact($vars)));

			$data = $data_ary;

			$this->notification_manager->add_notifications(array(
				'notification.type.quote',
				'notification.type.bookmark',
				'notification.type.post',
			), $notification_data);

			// Update search index
			$this->helper->update_search_index($merge_post_data);

			//Generate redirection URL and redirecting
			$params = $add_anchor = '';
			$params .= '&amp;t=' . $data['topic_id'];
			$params .= '&amp;p=' . $data['post_id'];
			$add_anchor = '#p' . $data['post_id'];
			$url = "{$this->phpbb_root_path}viewtopic.$this->php_ext";
			$url = append_sid($url, 'f=' . (int) $data['forum_id'] . $params) . $add_anchor;

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
			 * @var	int		post_visibility		Variable containing the post visibility
			 * @var	bool	update_message		Flag indicating if the post will be updated
			 * @var	bool	update_search_index	Flag indicating if the search index will be updated
			 * @var	string	url					The "Return to topic" URL
			 * @since 2.0.0
			 * @changed 2.1.1					Add post_visibility variable
			 */
			$vars = [
				'mode',
				'subject',
				'username',
				'topic_type',
				'poll',
				'data',
				'post_visibility',
				'update_message',
				'update_search_index',
				'url',
			];
			extract($this->phpbb_dispatcher->trigger_event('rxu.postsmerging.posts_merging_end', compact($vars)));

			redirect($url);
		}
	}

	/**
	 * Set posts creation time to display
	 *
	 * @param \phpbb\event\data	$event		Event object
	 * @param string			$eventname	Name of the event
	 */
	public function modify_topic_last_post_time($event, $eventname)
	{
		$data_flag = ($eventname == 'core.display_forums_modify_template_vars') ? 'forum' : 'topic';
		$data_row = $event[$data_flag . '_row'];
		$row = $event['row'];

		if ($row[$data_flag . '_last_post_id'])
		{
			$data_row['LAST_POST_TIME'] = $this->user->format_date(isset($row['post_created']) ? (int) $row['post_created'] : (int) $row[$data_flag . '_last_post_time']);
			$data_row['LAST_POST_TIME_RFC3339'] = gmdate(DATE_RFC3339, isset($row['post_created']) ? (int) $row['post_created'] : (int) $row[$data_flag . '_last_post_time']);
		}

		$event[$data_flag . '_row'] = $data_row;
	}

	/**
	 * Modify sql queries to select post creation time
	 *
	 * @param \phpbb\event\data	$event		Event object
	 * @param string			$eventname	Name of the event
	 */
	public function modify_sql($event, $eventname)
	{
		switch ($eventname)
		{
			case 'core.viewtopic_get_post_data':
			case 'core.display_forums_modify_sql':
				$sql = 'sql_ary';
				$field = ($eventname == 'core.viewtopic_get_post_data') ? 'p.post_id' : 'f.forum_last_post_id';
			break;

			case 'core.viewforum_get_topic_data':
				$sql = 'sql_array';
				$field = 't.topic_last_post_id';
			break;
		}

		$$sql = $event[$sql];
		$$sql['SELECT'] .= ', pm.post_created';
		$$sql['LEFT_JOIN'] = array_merge($$sql['LEFT_JOIN'], [
				[
					'FROM'	=> [$this->postsmerging_table => 'pm'],
					'ON'	=> "$field = pm.post_id",
				],
		]);
		$event[$sql] = $$sql;
	}

	/**
	 * Inject posts creation time data to the topic rowset
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function modify_viewtopic_rowset($event)
	{
		$row = $event['row'];
		$rowset = $event['rowset_data'];
		$rowset = array_merge($rowset, ['post_created'	=> !is_null($row['post_created']) ? $row['post_created'] : 0]);
		$event['rowset_data'] = $rowset;
	}

	/**
	 * Inject posts creation time data to postrow to display
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function modify_viewtopic_postrow($event)
	{
		$view = $this->request->variable('view', '');
		$row = $event['row'];
		$post_row = $event['post_row'];
		$post_time = ($row['post_created']) ?: $row['post_time'];
		$post_row['POST_DATE'] = $this->user->format_date($post_time, false, ($view == 'print') ? true : false);
		$event['post_row'] = $post_row;
	}

	/**
	 * Check the option to ignore posts merging in reply form
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function get_posts_merging_option($event)
	{
		$post_data = (isset($event['post_data'])) ? $event['post_data'] : $event['topic_data'];
		$forum_id = (int) $event['forum_id'];
		$topic_id = (isset($event['topic_id'])) ? (int) $event['topic_id'] : (int) $post_data['topic_id'];
		$mode = (isset($event['mode'])) ? $event['mode'] : false;

		if ($this->auth->acl_get('u_postsmerging') && $this->auth->acl_get('u_postsmerging_ignore')
			&& $this->merge_interval && $this->user->data['is_registered'] && (!$mode || in_array($mode, ['reply', 'quote']))
			&& (time() - (int) $post_data['topic_last_post_time']) < $this->merge_interval
			&& !$this->helper->excluded_from_merge(['forum_id' => $forum_id, 'topic_id' => $topic_id])
			&& $post_data['topic_last_poster_id'] == $this->user->data['user_id']
			&& $this->auth->acl_get('f_noapprove', $forum_id)
		)
		{
			$this->language->add_lang('posts_merging', 'rxu/postsmerging');
			$this->template->assign_vars([
				'POSTS_MERGING_OPTION'				=> true,
				'S_POSTS_MERGING_OPTION_CHECKED'	=> $this->request->is_set_post('posts_merging_option') ? ' checked="checked"' : '',
			]);
		}
	}

	/**
	 * Add permissions regarding posts merging
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function add_permission($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_postsmerging'] = ['lang' => 'ACL_U_POSTSMERGING', 'cat' => 'post'];
		$permissions['u_postsmerging_ignore'] = ['lang' => 'ACL_U_POSTSMERGING_IGNORE', 'cat' => 'post'];
		$event['permissions'] = $permissions;
	}
}
