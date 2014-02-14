<?php
/** 
*
* posting [English]
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

	'MERGE_SEPARATOR'		=> "\n\n[size=85][color=green]Added in %s %s %s:[/color][/size]\n",
	'MERGE_SUBJECT'			=> "[size=85][color=green]%s[/color][/size]\n",

	'D_SECONDS'  => array(
		1	=> '%d second',
		2	=> '%d seconds',
	),
	'D_MINUTES'  => array(
		1	=> '%d minute',
		2	=> '%d minutes',
	),
	'D_HOURS'    => array(
		1	=> '%d hour',
		2	=> '%d hours',
	),
	'D_MDAY'     => array(
		1	=> '%d day',
		2	=> '%d days',
	),
	'D_MON'      => array(
		1	=> '%d month',
		2	=> '%d months',
	),
	'D_YEAR'     => array(
		1	=> '%d year',
		2	=> '%d years',
	),
	
// ACP block
	'MERGE_INTERVAL'				=> 'Posts merging interval',
	'MERGE_INTERVAL_EXPLAIN'		=> 'If a user submit more than 2 posts in this time period, posts will be merged into one post. Information about the time passed from the previous post submitting will be added (for each post). Leave blank or set 0 to disable posts merging.',
	'MERGE_NO_TOPICS'				=> 'Excluded topics',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Comma separated list of topics ids where posts merging should not be applied to, when the feature is enabled.',
	'MERGE_NO_FORUMS'				=> 'Excluded forums',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Comma separated list of forums ids where posts merging should not be applied to, when the feature is enabled.',
));
