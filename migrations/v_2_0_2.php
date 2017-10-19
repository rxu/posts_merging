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

class v_2_0_2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['posts_merging_version']) && version_compare($this->config['posts_merging_version'], '2.0.2', '>=');
	}

	static public function depends_on()
	{
			return array('\rxu\PostsMerging\migrations\v_2_0_1');
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.update', array('posts_merging_version', '2.0.2')),
		);
	}
}
