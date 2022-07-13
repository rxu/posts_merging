<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * French translation by Galixte (http://www.galixte.com)
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
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Sur cette page il est possible modifier les paramètres de l’extension « Fusion des messages ».',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Aperçu',
	'MERGE_INTERVAL'				=> 'Intervalle de fusion des messages',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Lorsqu’un utilisateur envoie plus de deux messages durant ce laps de temps, les messages sont fusionnés en un seul. Pour chaque message fusionné, il sera ajouté des informations sur le temps écoulé depuis l’envoi du précédent message. Laissez ce champ vide ou à 0 pour désactiver cette fonctionnalité.',
	'MERGE_NO_TOPICS'				=> 'Sujets exclus',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Indiquez les ID des sujets, séparés par une virgule, à exclure lorsque la fusion des messages est activée.',
	'MERGE_NO_FORUMS'				=> 'Forums exclus',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Sélectionnez les forums <strong>à exclure de fonctionnalité de fusion des messages</strong>. Ne sélectionnez aucun forum pour utiliser la fonctionnalité de fusion des messages dans tous les forums.<br />Pour sélectionner / désélectionner plusieurs forums, en utilisant la bonne combinaison du clavier et de la souris en fonction de votre ordinateur ou navigateur.',
	'MERGE_SEPARATOR'				=> 'Séparateur',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Permet de configurer le texte séparateur qui sera affiché entre chaque message fusionné.<br />Il est possible d’utiliser les BBCodes qui seront analysés en fonction des paramètres du forum ou des messages.<br /><br />Il est également possible d’utiliser une clé de langue présente dans le répertoire de la langue utilisée, telle que : {L_<em>&lt;NOMDELACLE&gt;</em>} où <em>&lt;NOMDELACLE&gt;</em> est le nom de la clé de langue que l’on souhaite afficher. Par exemple, {L_WROTE} affiche le mot « Écrire » ou sa traduction en fonction de la langue utilisée par l’utilisateur.<br /><br />Utiliser <em>{TIME}</em> afin d’inclure dans le texte séparateur le temps écoulé entre les messages fusionnés.',
]);
