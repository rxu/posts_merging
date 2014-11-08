<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* Dutch translation by Dutch Translators (https://github.com/dutch-translators)
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
	'ACP_POSTS_MERGING'				=> 'Berichten samenvoegen',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Hier kun je de instellingen beheren voor de "Berichten samenvoegen" extensie.',
	'MERGE_INTERVAL'				=> 'Berichten samenvoegen interval',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Als een gebruiker meer dan 2 berichten plaatst binnen deze tijd, zullen de berichten worden samengevoegd tot 1 bericht. Informatie over de tijd van het vorige bericht zal bij het bericht worden geplaatst (voor elk bericht). Laat open of vul 0 in om uit te schakelen.',
	'MERGE_NO_TOPICS'				=> 'Onderwerpen uitsluiten',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Gescheiden met een komma de lijst met onderwerp ids waar berichten niet worden samengevoegd, als de functie is ingeschakeld.',
	'MERGE_NO_FORUMS'				=> 'Forums uitsluiten',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Gescheiden met een komma de lijst met forum ids waar berichten niet worden samengevoegd, als de functie is ingeschakeld.',
));
