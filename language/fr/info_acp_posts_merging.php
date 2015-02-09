<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* French translation by Galixte (http://www.galixte.com)
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
	'ACP_POSTS_MERGING'				=> 'Fusion des messages',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Ici vous pouvez appliquer les paramètres de l’extension “Fusion des messages”.',
	'MERGE_INTERVAL'				=> 'Intervalle de fusion des messages',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Si un utilisateur envoie plus de deux messages dans ce laps de temps, les messages seront fusionnés en un seul message. L’information concernant le temps écoulé depuis le précédant message envoyé sera ajoutée (pour chaque message). Laissez vide ou à  0 pour désactiver cette fonctionnalité.',
	'MERGE_NO_TOPICS'				=> 'Sujets exclus',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Si cette fonctionnalité est activée, indiquez les IDs des sujets pour lesquels vous ne souhaitez par activer cette fonctionnalité (séparés par une virgule).',
	'MERGE_NO_FORUMS'				=> 'Forums exclus',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Si cette fonctionnalité est activée, indiquez les IDs des forums contenant les sujets pour lesquels vous ne souhaitez par activer cette fonctionnalité (séparés par une virgule).',
));
