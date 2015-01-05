<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
 * Translated By : Basil Taha Alhitary - www.alhitary.net
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
	'MERGE_SEPARATOR'		=> "\n\n[size=85][color=green]تمت الإضافة في %s :[/color][/size]\n",
	'MERGE_SUBJECT'			=> "[size=85][color=green]%s[/color][/size]\n",
	'POSTS_MERGING_OPTION'	=> 'لا تدمج مع المشاركة السابقة',

	'D_SECONDS'  => array(
		1	=> '%d ثانية',
		2	=> '%d ثواني',
	),
	'D_MINUTES'  => array(
		1	=> '%d دقيقة',
		2	=> '%d دقائق',
	),
	'D_HOURS'    => array(
		1	=> '%d ساعة',
		2	=> '%d ساعات',
	),
	'D_MDAY'     => array(
		1	=> '%d يوم',
		2	=> '%d أيام',
	),
	'D_MON'      => array(
		1	=> '%d شهر',
		2	=> '%d شهور',
	),
	'D_YEAR'     => array(
		1	=> '%d سنة',
		2	=> '%d سنوات',
	),
));
