<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rxu\PostsMerging\migrations;

class add_permissions extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
			return array('\rxu\PostsMerging\migrations\v_2_0_3');
	}

	public function update_data()
	{
		return array(
			// Add permission
			array('permission.add', array('u_postsmerging', true)),
			array('permission.add', array('u_postsmerging_ignore', true)),

			// Set permissions
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_postsmerging')),
			array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_postsmerging')),
			array('permission.permission_set', array('ROLE_USER_FULL', 'u_postsmerging_ignore')),
			array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_postsmerging_ignore')),
		);
	}
}
