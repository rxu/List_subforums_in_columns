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
 * French translation by Galixte (http://www.galixte.com)
 */

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine



$lang = array_merge($lang, [
	'SUBFORUMSLIST_TYPE'			=> 'Nombre de colonnes pour la liste des sous-forums ',
	'SUBFORUMSLIST_TYPE_EXPLAIN'	=> 'Entrer le nombre de colonnes pour séparer la liste des sous-forums dans la légende de ce forum. Paramétrer à 0 pour désactiver cette option.',
]);
