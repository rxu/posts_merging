<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/
/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
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
$lang = array_merge($lang, array(
	'ACP_POSTS_MERGING'				=> 'Postituste ühendamine',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Siin saad seadistada laienduse "Postituste Ühendamine" seadeid.',
	'MERGE_INTERVAL'				=> 'Postituste ühendamise ajavahemik',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Kui kasutaja sisestab rohkem kui 2 postitust sama aja sees, siis need ühendatakse üheks postituseks. Jäta tühjaks või kirjuta väärtuseks "0", et keelata postituste ühendamine.',
	'MERGE_NO_TOPICS'				=> 'Välistatud teemad',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Eralda komaga teemade ID\'d, kus postituste ühendamist ei tohiks mitte mingil juhul toimuda, kui laiendus on lubatud.',
	'MERGE_NO_FORUMS'				=> 'Välistatud foorumid',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Eralda komaga foorumi ID\'d, kus foorumite ühendamist ei tohiks mitte mingil juhul toimuda, kui laiendus on lubatud.',
));
