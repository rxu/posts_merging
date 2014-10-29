<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rxu\PostsMerging\acp;

class posts_merging_info
{
	function module()
	{
		return array(
			'filename'	=> '\rxu\PostsMerging\acp\posts_merging_module',
			'title'		=> 'ACP_POSTS_MERGING',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'config_posts_merging'		=> array('title' => 'ACP_POSTS_MERGING', 'auth' => 'ext_rxu/PostsMerging && acl_a_board', 'cat' => array('ACP_POSTS_MERGING')),
			),
		);
	}
}
