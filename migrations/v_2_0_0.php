<?php
/**
*
* @package ListSubforumsInColumns
* @copyright (c) 2014 Палыч (gfksx)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace gfksx\ListSubforumsInColumns\migrations;

class v_2_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['list_subforums_in_columns_version']) && version_compare($this->config['list_subforums_in_columns_version'], '2.0.0', '>=');
	}

	static public function depends_on()
	{
			return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return 	array(
			'add_columns' => array(
				$this->table_prefix . 'forums' => array(
					'forum_subforumslist_type' => array('TINT:4', '0'),
				),
			),
		);
	}

	public function revert_schema()
	{
		return 	array(	
			'drop_columns' => array(
				$this->table_prefix . 'forums' => array('forum_subforumslist_type'),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Add configs
			// Current version
			array('config.add', array('list_subforums_in_columns_version', '2.0.0')),
		);
	}
}
