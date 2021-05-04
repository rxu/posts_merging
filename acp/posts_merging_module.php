<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\postsmerging\acp;

class posts_merging_module
{
	var $u_action;
	var $new_config = [];

	function main($id, $mode)
	{
		global $phpbb_container;

		/** @var \phpbb\config\config $config */
		$config = $phpbb_container->get('config');

		/** @var \phpbb\config\db_text $config_text */
		$config_text = $phpbb_container->get('config_text');

		/** @var \phpbb\language\language $language */
		$language = $phpbb_container->get('language');

		/** @var \phpbb\request\request $request */
		$request  = $phpbb_container->get('request');

		/** @var \phpbb\template\template $template */
		$template = $phpbb_container->get('template');

		/** @var string $php_ext php_ext */
		$php_ext = $phpbb_container->getParameter('core.php_ext');

		/** @var string $phpbb_root_path */
		$phpbb_root_path = $phpbb_container->getParameter('core.root_path');

		$this->page_title = 'ACP_POSTS_MERGING';
		$this->tpl_name = 'acp_posts_merging';

		$submit = $request->is_set_post('submit');
		$preview = $request->is_set_post('preview');

		add_form_key('config_posts_merging');

		$display_vars = [
			'title'	=> 'ACP_POSTS_MERGING',
			'vars'	=> [
				'legend1'	=> 'GENERAL_OPTIONS',
					'merge_interval'	=> ['lang' => 'MERGE_INTERVAL', 'validate' => 'int:0', 'type' => 'number:0:9999', 'explain' => true, 'append' => ' ' . $language->lang('HOURS')],
					'merge_no_forums'	=> ['lang' => 'MERGE_NO_FORUMS', 'validate' => 'string', 'type' => 'custom', 'method' => 'select_merge_no_forums', 'explain' => true],
					'merge_no_topics'	=> ['lang' => 'MERGE_NO_TOPICS', 'validate' => 'string', 'type' => 'text:5:255', 'explain' => true],
				'legend2'	=> 'MERGE_SEPARATOR',
			],
		];

		if (isset($display_vars['lang']))
		{
			$language->add_lang($display_vars['lang']);
		}
		$language->add_lang(['posting']);

		$this->new_config = $config;
		$cfg_array = $request->is_set('config') ? $request->variable('config', ['' => ''], true) : $this->new_config;
		$cfg_array['merge_no_forums'] = ($submit || $preview) ? implode(',', $request->variable('merge_no_forums', ['' => ''])) : $cfg_array['merge_no_forums'];
		$posts_merging_separator_text = $request->variable('posts_merging_separator_text', '', true);
		$error = [];

		// We validate the complete config if wished
		validate_config_vars($display_vars['vars'], $cfg_array, $error);

		if ($submit && !check_form_key('config_posts_merging'))
		{
			$error[] = $language->lang('FORM_INVALID');
		}

		// Do not write values if there is an error
		if (count($error))
		{
			$submit = false;
		}

		// We go through the display_vars to make sure no one is trying to set variables he/she is not allowed to...
		foreach ($display_vars['vars'] as $config_name => $null)
		{
			if (!isset($cfg_array[$config_name]) || strpos($config_name, 'legend') !== false)
			{
				continue;
			}

			$this->new_config[$config_name] = $config_value = $cfg_array[$config_name];

			if ($submit)
			{
				$config->set($config_name, $config_value);
			}
		}

		if ($submit)
		{
			$config_text->set('posts_merging_separator_text', $posts_merging_separator_text);

			trigger_error($language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Output relevant page
		foreach ($display_vars['vars'] as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', [
					'S_LEGEND'	=> true,
					'LEGEND'	=> $language->lang($vars),
				]);

				continue;
			}

			$type = explode(':', $vars['type']);

			$l_explain = '';
			if ($vars['explain'] && isset($vars['lang_explain']))
			{
				$l_explain = $language->lang($vars['lang_explain']);
			}
			else if ($vars['explain'])
			{
				$l_explain = $language->lang($vars['lang'] . '_EXPLAIN');
			}

			$content = build_cfg_template($type, $config_key, $this->new_config, $config_key, $vars);

			if (empty($content))
			{
				continue;
			}

			$template->assign_block_vars('options', [
				'KEY'			=> $config_key,
				'TITLE'			=> $language->lang($vars['lang']),
				'S_EXPLAIN'		=> $vars['explain'],
				'TITLE_EXPLAIN'	=> $l_explain,
				'CONTENT'		=> $content,
			]);

			unset($display_vars['vars'][$config_key]);
		}

		$posts_merging_separator_text = ($posts_merging_separator_text) ?: $config_text->get('posts_merging_separator_text');

		include_once($phpbb_root_path . 'includes/functions_display.' . $php_ext);

		/*
		* Constant merge separator preview
		*/
		include_once($phpbb_root_path . 'includes/message_parser.' . $php_ext);

		// Prepare message separator
		$language->add_lang('posts_merging', 'rxu/postsmerging');

		// Calculate the time interval
		$helper = $phpbb_container->get('rxu.postsmerging.helper');
		$current_time = time();
		$interval = $helper->get_time_interval(strtotime('3 hours 17 minutes 56 seconds'), $current_time);
		$time = [];
		$time[] = ($interval->h) ? $language->lang('D_HOURS', $interval->h) : null;
		$time[] = ($interval->i) ? $language->lang('D_MINUTES', $interval->i) : null;
		$time[] = ($interval->s) ? $language->lang('D_SECONDS', $interval->s) : null;

		// Allow using language variables like {L_LANG_VAR}
		$posts_merging_separator_text_prewiew = preg_replace_callback(
			'/{L_([A-Z0-9_]+)}/',
			function ($matches) use ($language)
			{
				return $language->lang($matches[1]);
			},
			$posts_merging_separator_text
		);

		// Eval linefeeds and generate the separator, time interval included
		$posts_merging_separator_text_prewiew = sprintf(str_replace('\n', "\n", $posts_merging_separator_text_prewiew), implode(' ', $time));

		$message_parser = new \parse_message($posts_merging_separator_text_prewiew);
		// Allowing Quote BBCode
		$message_parser->parse(true, true, true, true, true, true, true, true);
		// Now parse it for displaying
		$separator_preview = $message_parser->format_display(true, true, true, false);
		unset($message_parser);
		$template->assign_var('SEPARATOR_PREVIEW', $separator_preview);
		/*
		* Constant merge separator preview end
		*/

		$template->assign_vars([
			'POSTS_MERGING_SEPARATOR_TEXT'	=> $posts_merging_separator_text,
			'S_SMILIES_ALLOWED'		=> true,
			'S_BBCODE_IMG'			=> true,
			'S_BBCODE_FLASH'		=> true,
			'S_LINKS_ALLOWED'		=> true,
			'U_ACTION'				=> $this->u_action,
		]);

		// Assigning custom bbcodes
		display_custom_bbcodes();
	}

	function select_merge_no_forums($value, $key)
	{
		global $phpbb_container;

		/** @var \phpbb\config\config $config */
		$config = $phpbb_container->get('config');

		$merge_no_forums = explode(',', $config['merge_no_forums']);
		$forum_list = make_forum_select(false, false, true, true, true, false, true);

		// Build forum options
		$s_forum_options = '<select id="' . $key . '" name="' . $key . '[]" multiple="multiple">';
		foreach ($forum_list as $f_id => $f_row)
		{
			$f_row['selected'] = in_array($f_id, $merge_no_forums);

			$s_forum_options .= '<option value="' . $f_id . '"' . (($f_row['selected']) ? ' selected="selected"' : '') . (($f_row['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $f_row['padding'] . $f_row['forum_name'] . '</option>';
		}
		$s_forum_options .= '</select>';

		return $s_forum_options;
	}
}
