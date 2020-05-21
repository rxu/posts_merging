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

class posts_merging_info
{
	function module()
	{
		return [
			'filename'	=> '\rxu\postsmerging\acp\posts_merging_module',
			'title'		=> 'ACP_POSTS_MERGING',
			'version'	=> '3.0.0',
			'modes'		=> [
				'config_posts_merging'	=> ['title' => 'ACP_POSTS_MERGING', 'auth' => 'ext_rxu/postsmerging && acl_a_board', 'cat' => ['ACP_POSTS_MERGING']],
			],
		];
	}
}
