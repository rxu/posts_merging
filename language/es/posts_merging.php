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
	'MERGE_SEPARATOR'		=> "\n\n[size=85][color=green]Añadido en %s:[/color][/size]\n",
	'MERGE_SUBJECT'			=> "[size=85][color=green]%s[/color][/size]\n",
	'POSTS_MERGING_OPTION'	=> 'Do not merge with previous post',

	'D_SECONDS'  => array(
		1	=> '%d segundo',
		2	=> '%d segundos',
	),
	'D_MINUTES'  => array(
		1	=> '%d minuto',
		2	=> '%d minutos',
	),
	'D_HOURS'    => array(
		1	=> '%d hora',
		2	=> '%d horas',
	),
	'D_MDAY'     => array(
		1	=> '%d día',
		2	=> '%d días',
	),
	'D_MON'      => array(
		1	=> '%d mes',
		2	=> '%d meses',
	),
	'D_YEAR'     => array(
		1	=> '%d año',
		2	=> '%d años',
	),
));
