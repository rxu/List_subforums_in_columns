<?php
/**
 *
 * List subforums in columns.
 * Allows to choose how subforums are listed - in line or in column(s) on per-forum basis.
 * An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\listsubforumsincolumns;

/**
* Main extension class for this extension.
*/
class ext extends \phpbb\extension\base
{
	/**
	* Check whether or not the extension can be enabled.
	* The current phpBB version should meet or exceed
	* the minimum version required by this extension: 3.2.0
	*
	* @return bool
	* @access public
	*/
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=');
	}
}
