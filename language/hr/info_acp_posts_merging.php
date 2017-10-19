<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* Croatian translation by Ančica Sečan (http://ancica.sunceko.net)
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
	'ACP_POSTS_MERGING'				=>'Spajanje postova',
	'ACP_POSTS_MERGING_EXPLAIN'		=>'Ovdje možeš podesiti postavke ekstenzije “Spajanje postova”.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Separator preview',
	'MERGE_INTERVAL'				=>'Interval spajanja postova',
	'MERGE_INTERVAL_EXPLAIN'		=>'Ukoliko korisnik/ca posta više od dva uzastopna posta, u postavljenom vremenskom intervalu, isti će biti spojeni u jedan post.<br />Za svaki [prethodni] post, prilikom spajanja postova, bit će dodano (i) proteklo vrijeme od postanja prethodnog posta.<br /> Za onemogućavanje spajanja postova, vrijednost postavi na 0 ili ostavi praznim.',
	'MERGE_NO_TOPICS'				=>'Izuzete teme',
	'MERGE_NO_TOPICS_EXPLAIN'		=>'IDovi tema koje će biti izuzete [od spajanja], odvojeni [sa] "," (a) kada je spajanje postova omogućeno.',
	'MERGE_NO_FORUMS'				=>'Izuzeti forumi',
	'MERGE_NO_FORUMS_EXPLAIN'		=>'IDovi foruma koji će biti izuzeti [od spajanja], odvojeni [sa] "," (a) kada je spajanje postova omogućeno.',
	'MERGE_SEPARATOR'				=> 'Separator',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Here you can configure the separator which will appear between the merged message parts.<br />You can use BBCodes which will be parsed in according to the board or message settings.<br /><br />You can also use any language string present in your language/ directory like this: {L_<em>&lt;STRINGNAME&gt;</em>} where <em>&lt;STRINGNAME&gt;</em> is the name of the translated string you want to add. For example, {L_WROTE} will be displayed as “wrote” or its translation according to user’s locale.<br /><br />Use <em>&#37;s</em> placeholder (once) to include the time passed between merging in the separator.',
	'MERGE_SEPARATOR'				=>'Razdjelnik',
	'MERGE_SEPARATOR_EXPLAIN'		=>'Ovdje možeš podesiti razdjelnik koji će se pojaviti između spojenih dijelova poruke.<br />Možeš koristiti BBKodove koji će biti parsirani u skladu s postavkama foruma/poruka.<br /><br />Također, možeš koristiti bilo koju, u tvojoj jezičnoj mapi postojeću, jezičnu varijablu/niz tipa: {L_<em>&lt;STRINGNAME&gt;</em>} gdje je <em>&lt;STRINGNAME&gt;</em> ime prevedene jezične varijable/niza kojeg želiš dodati. Npr., {L_WROTE} će biti prikazano kao “wrote” odnosno kao što je prevedeno u lokaliziranom jezičnom paketu [“je napisao/la”].<br /><br />Za uključivanje vremena prošlog između spajanja razdjelnikom, koristi [jednom] <em>&#37;s</em>.',
));
