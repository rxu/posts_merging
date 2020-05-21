<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\postsmerging\migrations;

class v_3_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'postsmerging');
	}

	static public function depends_on()
	{
			return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_schema()
	{
		return [
			'add_tables' => [
				$this->table_prefix . 'postsmerging' => [
					'COLUMNS'	=> [
						'post_id'		=> ['UINT', 0],
						'post_created'	=> ['TIMESTAMP', 0],
					],
					'PRIMARY_KEY'	=> ['post_id'],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables'	=> [$this->table_prefix . 'postsmerging'],
		];
	}

	public function update_data()
	{
		return [
			// Add configs
			['config.add', ['merge_interval', 3]],
			['config.add', ['merge_no_forums', 0]],
			['config.add', ['merge_no_topics', 0]],
			['config_text.add', ['posts_merging_separator_text', '{L_MERGE_SEPARATOR}']],

			// Add ACP modules
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_POSTS_MERGING']],
			['module.add', ['acp', 'ACP_POSTS_MERGING', [
					'module_basename'	=> '\rxu\postsmerging\acp\posts_merging_module',
					'module_langname'	=> 'ACP_POSTS_MERGING',
					'module_mode'		=> 'config_posts_merging',
					'module_auth'		=> 'ext_rxu/postsmerging && acl_a_board',
			]]],

			// Add permissions
			['permission.add', ['u_postsmerging', true]],
			['permission.add', ['u_postsmerging_ignore', true]],

			// Set permissions
			['permission.permission_set', ['ROLE_USER_FULL', 'u_postsmerging']],
			['permission.permission_set', ['ROLE_USER_STANDARD', 'u_postsmerging']],
			['permission.permission_set', ['ROLE_USER_FULL', 'u_postsmerging_ignore']],
			['permission.permission_set', ['ROLE_USER_STANDARD', 'u_postsmerging_ignore']],
		];
	}
}
