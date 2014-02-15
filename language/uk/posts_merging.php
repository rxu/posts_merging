<?php
/** 
*
* posts_merging [Ukrainian]
*
* @package posts_merging
* @copyright (c) 2014 Ruslan Uzdenov (rxu)
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
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
	'MERGE_SEPARATOR'		=> "\n\n[size=85][color=green]Відправлено через %s %s %s:[/color][/size]\n",
	'MERGE_SUBJECT'			=> "[size=85][color=green]%s[/color][/size]\n",

	'D_SECONDS'  => array(
		1	=> '%d секунду',
		2	=> '%d секунди',
		3	=> '%d секунд'
	),
	'D_MINUTES'  => array(
		1	=> '%d хвилину',
		2	=> '%d хвилини',
		3	=> '%d хвилин'
	),
	'D_HOURS'    => array(
		1	=> '%d годину',
		2	=> '%d години',
		3	=> '%d годин'
	),
	'D_MDAY'     => array(
		1	=> '%d день',
		2	=> '%d дня',
		3	=> '%d днів'
	),
	'D_MON'      => array(
		1	=> '%d місяць',
		2	=> '%d місяці',
		3	=> '%d місяців'
	),
	'D_YEAR'     => array(
		1	=> '%d рік',
		2	=> '%d року',
		3	=> '%d років'
	),
));
