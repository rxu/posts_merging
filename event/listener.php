<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rxu\PostsMerging\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var rxu\PostsMerging\core\helper */
	protected $helper;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* @var	\phpbb\request\type_cast_helper_interface
	*/
	protected $type_cast_helper;

	/** @var int merge_interval */
	protected $merge_interval;

	/**
	* Constructor
	*
	* @param \phpbb\config\config                      $config                Config object
	* @param \phpbb\config\db_text                     $config_text           Config_text object
	* @param \phpbb\auth\auth                          $auth                  Auth object
	* @param \phpbb\request\request_interface          $request               Request object
	* @param \phpbb\user                               $user                  User object
	* @param \phpbb\notification\manager               $notification_manager  Notification manager object
	* @param \phpbb\event\dispatcher_interface         $phpbb_dispatcher      Event dispatcher object
	* @param \phpbb\template\template                  $template              Template object
	* @param rxu\PostsMerging\core\helper              $helper                The extension helper object
	* @param string                                    $phpbb_root_path       phpbb_root_path
	* @param string                                    $php_ext               phpEx
	* @param \phpbb\request\type_cast_helper_interface $type_cast_helper      The type cast helper object
	* @return \rxu\AdvancedWarnings\event\listener
	* @access public
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\config\db_text $config_text, \phpbb\auth\auth $auth, \phpbb\request\request_interface $request, \phpbb\user $user, \phpbb\notification\manager $notification_manager, \phpbb\event\dispatcher_interface $phpbb_dispatcher, \phpbb\template\template $template, $helper, $phpbb_root_path, $php_ext, \phpbb\request\type_cast_helper_interface $type_cast_helper = null)
	{
		$this->user = $user;
		$this->auth = $auth;
		$this->request = $request;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->notification_manager = $notification_manager;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->template = $template;
		$this->helper = $helper;
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
		return array(
			'core.modify_submit_post_data'			=> 'posts_merging',
			'core.viewtopic_post_rowset_data'		=> 'modify_viewtopic_rowset',
			'core.viewtopic_modify_post_row'		=> 'modify_viewtopic_postrow',
			'core.posting_modify_template_vars'		=> 'get_posts_merging_option',
			'core.viewtopic_modify_page_title'		=> 'get_posts_merging_option',
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

		$current_time = time();

		$do_not_merge_with_previous = $this->request->variable('posts_merging_option', false);

		if (!$do_not_merge_with_previous && !$this->helper->post_needs_approval($data)
			&& in_array($mode, array('reply', 'quote')) && $this->merge_interval
			&& !$this->helper->excluded_from_merge($data)
		)
		{
			$merge_post_data = $this->helper->get_last_post_data($data);

			// Do not merge if there's no last post data, the post is locked or allowed merge period has left
			if (!$merge_post_data || $merge_post_data['post_edit_locked'] ||
				(($current_time - (int) $merge_post_data['topic_last_post_time']) > $this->merge_interval)
				|| !$this->user->data['is_registered']
			)
			{
				return;
			}

			// Also, don't let user to violate attachments limit by posts merging
			// In this case, also don't merge posts and return
			// Exceptions are administrators and forum moderators
			$num_old_attachments = $this->helper->count_post_attachments((int) $merge_post_data['post_id']);
			$num_new_attachments = sizeof($data['attachment_data']);
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
				$merge_post_data['post_text'] = preg_replace('#\[attachment=([0-9]+)\](.*?)\[\/attachment\]#e', "'[attachment='.(\\1 + $num_new_attachments).']\\2[/attachment]'", $merge_post_data['post_text']);
			}

			// Prepare message separator
			$separator = (string) $this->config_text->get('posts_merging_separator_text');
			$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');

			// Calculate the time interval
			$interval = $this->helper->get_time_interval($current_time, $merge_post_data['post_time']);
			$time = array();
			$time[] = ($interval->h) ? $this->user->lang('D_HOURS', $interval->h) : null;
			$time[] = ($interval->i) ? $this->user->lang('D_MINUTES', $interval->i) : null;
			$time[] = ($interval->s) ? $this->user->lang('D_SECONDS', $interval->s) : null;

			// Allow using language variables like {L_LANG_VAR}
			$separator = preg_replace_callback(
				'/{L_([A-Z0-9_]+)}/',
				function ($matches) { return $this->user->lang($matches[1]); },
				$separator
			);

			// Eval linefeeds and generate the separator, time interval included
			$separator = sprintf(str_replace('\n', "\n", $separator), implode(' ', $time));

			// Merge subject
			if (!empty($subject) && $subject != $merge_post_data['post_subject'] && $merge_post_data['post_id'] != $merge_post_data['topic_first_post_id'])
			{
				$separator .= sprintf($this->user->lang['MERGE_SUBJECT'], $subject);
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

			// If this is the first merging for current post, save original post time within the post_created field
			// Update post time with the current time and submit post to the database
			$merge_post_data['post_created'] = ($merge_post_data['post_created']) ?: $merge_post_data['post_time'];
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
			$notification_data = array_merge($data, array(
				'topic_title'		=> (isset($data['topic_title'])) ? $data['topic_title'] : $subject,
				'post_username'		=> $username,
				'poster_id'			=> (int) $data['poster_id'],
				'post_text'			=> $data['message'],
				'post_time'			=> $merge_post_data['post_time'],
				'post_subject'		=> $subject,
			));
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

			redirect($url);
		}
	}

	public function modify_viewtopic_rowset($event)
	{
		$row = $event['row'];
		$rowset = $event['rowset_data'];
		$rowset = array_merge($rowset, array('post_created'	=> $row['post_created']));
		$event['rowset_data'] = $rowset;
	}

	public function modify_viewtopic_postrow($event)
	{
		$view = $this->request->variable('view', '');
		$row = $event['row'];
		$post_row = $event['post_row'];
		$post_time = ($row['post_created']) ?: $row['post_time'];
		$post_row['POST_DATE'] = $this->user->format_date($post_time, false, ($view == 'print') ? true : false);
		$event['post_row'] = $post_row;
	}

	public function get_posts_merging_option($event)
	{
		$post_data = (isset($event['post_data'])) ? $event['post_data'] : $event['topic_data'];
		$forum_id = (int) $event['forum_id'];
		$topic_id = (isset($event['topic_id'])) ? (int) $event['topic_id'] : (int) $post_data['topic_id'];
		$mode = (isset($event['mode'])) ? $event['mode'] : false;

		if ($this->merge_interval && $this->user->data['is_registered'] && (!$mode || in_array($mode, array('reply', 'quote')))
			&& (time() - (int) $post_data['topic_last_post_time']) < $this->merge_interval
			&& !$this->helper->excluded_from_merge(array('forum_id' => $forum_id, 'topic_id' => $topic_id))
			&& $post_data['topic_last_poster_id'] == $this->user->data['user_id']
			&& $this->auth->acl_get('f_noapprove', $forum_id)
		)
		{
			$this->user->add_lang_ext('rxu/PostsMerging', 'posts_merging');
			$this->template->assign_vars(array('POSTS_MERGING_OPTION' => true));
		}
	}
}
