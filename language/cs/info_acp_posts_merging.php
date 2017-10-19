<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 phpBB Limited <https://www.phpbb.com>
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
	'ACP_POSTS_MERGING'				=> 'Slučování příspěvků',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Zde můžete zvolit nastavení rozšíření Slučování příspěvků.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Náhled oddělovače',
	'MERGE_INTERVAL'				=> 'Interval pro sloučení',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Pokud uživatel v zadaném intervalu odešle do stejného tématu další příspěvek, bude jeho obsah sloučen s prvním příspěvkem. Do příspěvku bude také přidána informace o tom, jak dlouhá doba uběhla mezi odesláním původního a nového příspěvku. Pokud chcete tuto funkcionalitu vypnout, nechte pole prázné, nebo zadejte 0.',
	'MERGE_NO_TOPICS'				=> 'Vyloučená témata',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Seznam ID témat, ve kterých nebude slučování aplikováno (oddělujte čárkou).',
	'MERGE_NO_FORUMS'				=> 'Vyloučená fóra',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Seznam ID fór, ve kterých nebude slučování aplikováno (oddělujte čárkou).',
	'MERGE_SEPARATOR'				=> 'Oddělovač',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Zde můžete nastavit podobu oddělovače, který bude zobrazen mezi sloučenými příspěvky.<br />Můžete používat tagy BBCode.<br /><br />Můžete také použít jakékoliv řetězce jazykových lokalizací ve složce language/ následujícím způsobem: {L_<em>&lt;ŘETĚZEC&gt;</em>} kde <em>&lt;ŘETĚZEC&gt;</em> je název přeloženého řetězce, který chcete přidat. Příklad: {L_WROTE} bude v příspěvku zobrazeno jako „napsal“ v závislosti na uživatelově nastavení jazyka.<br /><br />Pro zobrazení doby uplynulé mezi sloučením můžete použít následující zástupný symbol: <em>&#37;s</em> (jen jeden).',
));
