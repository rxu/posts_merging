<?php
/**
*
* @package posts_merging
* @copyright (c) 2014 Ruslan Uzdenov (rxu)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace rxu\posts_merging\acp;

class posts_merging_info
{
	function module()
	{
		return array(
			'filename'	=> '\rxu\posts_merging\acp\posts_merging_module',
			'title'		=> 'ACP_POSTS_MERGING',
			'version'	=> '2.0.0',
			'modes'		=> array(
				'config_posts_merging'		=> array('title' => 'ACP_POSTS_MERGING', 'auth' => 'acl_a_board', 'cat' => array('ACP_POSTS_MERGING')),
			),
		);
	}
}
