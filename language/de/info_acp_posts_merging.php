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
	'ACP_POSTS_MERGING'				=> 'Beiträge zusammenfügen',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Hier kannst du Einstellungen für die "Beiträge zusammenführen Erweiterung" vornehmen.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Separator preview',
	'MERGE_INTERVAL'				=> 'Zeitraum in dem Beiträge zusammengefügt werden können ',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Wenn ein User mehr als 2 Beiträge innerhalb dieses Zeitraums erstellt, werden die Beiträge zu einem Beitrag zusammengefasst. Informationen über die vergangene Zeit seit dem letzten Beitrag werden hinzugefügt (für jeden Beitrag). Leer lassen oder auf 0 setzen um das Zusammenfügen zu deaktivieren.',
	'MERGE_NO_TOPICS'				=> 'Ausgeschlossene Themen',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Komma getrennte Liste der Themen-IDs bei denen ein Zusammenfügen nicht stattfinden soll, wenn diese Funktion aktiviert ist.',
	'MERGE_NO_FORUMS'				=> 'Ausgeschlossene Foren',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Komma getrennte Liste der Foren-IDs bei denen ein Zusammenfügen nicht stattfinden soll, wenn diese Funktion aktiviert ist.',
	'MERGE_SEPARATOR'				=> 'Separator',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Hier kannst Du das Trennzeichen konfigurieren, die zwischen den zusammengeführten Beiträge erscheinen.<br />Du kannst BBCodes in entsprechend den Board-Einstellungen oder Nachricht analysiert werden soll.<br /><br />Du kannst auch eine beliebige Sprachzeichenfolge vorhanden in Ihrer Sprache/ Verzeichnis wie folgt: {L_<em>&lt;STRINGNAME&gt;</em>} wo <em>&lt;STRINGNAME&gt;</em> ist der Name des übersetzten String den Du hinzufügen möchtest. Zum Beispiel, {L_WROTE} wird angezeigt als \'schrieb\' oder die Übersetzung nach Gebietsschema des Benutzers.<br /><br />Verwende <em>&#37;s</em> Platzhalter (einmal), um die Zeit zwischen Zusammenführen im Separator zu übergeben.',
));
