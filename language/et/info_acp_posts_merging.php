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
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Separator preview',
	'MERGE_INTERVAL'				=> 'Postituste ühendamise ajavahemik',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Kui kasutaja sisestab rohkem kui 2 postitust sama aja sees, siis need ühendatakse üheks postituseks. Jäta tühjaks või kirjuta väärtuseks "0", et keelata postituste ühendamine.',
	'MERGE_NO_TOPICS'				=> 'Välistatud teemad',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Eralda komaga teemade ID\'d, kus postituste ühendamist ei tohiks mitte mingil juhul toimuda, kui laiendus on lubatud.',
	'MERGE_NO_FORUMS'				=> 'Välistatud foorumid',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Eralda komaga foorumi ID\'d, kus foorumite ühendamist ei tohiks mitte mingil juhul toimuda, kui laiendus on lubatud.',
	'MERGE_SEPARATOR'				=> 'Separator',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Here you can configure the separator which will appear between the merged message parts.<br />You can use BBCodes which will be parsed in according to the board or message settings.<br /><br />You can also use any language string present in your language/ directory like this: {L_<em>&lt;STRINGNAME&gt;</em>} where <em>&lt;STRINGNAME&gt;</em> is the name of the translated string you want to add. For example, {L_WROTE} will be displayed as “wrote” or its translation according to user’s locale.<br /><br />Use <em>&#37;s</em> placeholder (once) to include the time passed between merging in the separator.',
));
