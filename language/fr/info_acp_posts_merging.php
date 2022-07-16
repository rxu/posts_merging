<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * French translation by Galixte (http://www.galixte.com)
 * French translation review by phpBB-fr.com (https://www.phpbb-fr.com)
 *
 */

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
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
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, [
	'ACP_POSTS_MERGING'				=> 'Fusion des messages',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Cette page sert à modifier les paramètres de l’extension « Fusion des messages ».',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Aperçu',
	'MERGE_INTERVAL'				=> 'Intervalle de fusion des messages',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Lorsqu’un utilisateur publie deux messages consécutifs durant ce laps de temps, les messages sont fusionnés en un seul. Pour chaque message fusionné, il sera ajouté des informations sur le temps écoulé depuis l’envoi du précédent message. Laissez ce champ vide ou à 0 pour désactiver cette fonctionnalité.',
	'MERGE_NO_TOPICS'				=> 'Sujets exclus',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Indiquez les ID des sujets, séparés par une virgule, à exclure lorsque la fusion des messages est activée.',
	'MERGE_NO_FORUMS'				=> 'Forums exclus',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Sélectionnez les forums <strong>à exclure de la fonctionnalité de fusion des messages</strong>. Ne sélectionnez aucun forum pour utiliser la fonctionnalité de fusion des messages dans tous les forums.<br />Pour sélectionner / désélectionner plusieurs forums, utilisez la bonne combinaison du clavier et de la souris en fonction de votre ordinateur ou navigateur.',
	'MERGE_SEPARATOR'				=> 'Séparateur',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Configurez le texte séparateur qui sera inséré entre chaque message fusionné.<br />Vous pouvez utiliser les BBCodes, en fonction des paramètres définis au niveau du forum ou des messages.<br /><br />Vous pouvez également utiliser n’importe quelle clé de langue disponible dans le répertoire « language/ », telle que : {L_<em>&lt;NOMDELACLE&gt;</em>} où <em>&lt;NOMDELACLE&gt;</em> est le nom de la clé de langue que l’on souhaite utiliser. Par exemple, {L_WROTE} affiche le mot « a écrit » ou sa traduction en fonction de la langue utilisée par l’utilisateur.<br /><br />Utilisez <em>{TIME}</em> afin d’inclure dans le texte séparateur le temps écoulé entre les messages fusionnés.',
]);
