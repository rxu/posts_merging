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
	'ACP_POSTS_MERGING'				=> 'Unione dei messaggi',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Qui puoi modificare le impostazioni per l\'estensione Post merging.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Anteprima Separatore',
	'MERGE_INTERVAL'				=> 'Intervallo per l\'unione dei messaggi',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Se un utente invia più di due messaggi in questo periodo di tempo, i messaggi verranno uniti in un unico messaggio. Le informazioni riguardo al periodo di tempo passato dal precedente messaggio inviato verranno aggiunte (per ogni messaggio). Lascia bianco o impostalo a 0 per disabilitare l\'unione dei messaggi.',
	'MERGE_NO_TOPICS'				=> 'Argomenti esclusi',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Un elenco separato dalle virgole degli argomenti dove l\'unione dei messaggi non dovrebbe essere applicata, quando questa funzione è abilitata.',
	'MERGE_NO_FORUMS'				=> 'Forum esclusi',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'La funzione di unione dei messaggi <strong>verrà disabilitata nei forum selezionati</strong>. Nel caso nessun forum sia selezionato, la funzionalità verrà utilizzata in tutti i forum.<br />Puoi selezionare o deselezionare più forum tenendo premuto <samp>CTRL</samp> e cliccando.',
	'MERGE_SEPARATOR'				=> 'Separatore',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Qui puoi configurare il separatore che apparirà tra le due parti unite del messaggio.<br />PUoi usare i BBCode, che saranno elaborati in base alle impostazioni della Board o delle impostazioni dei messaggi.<br /><br />Qui puoi anche utilizzare ogni stringa linguistica presente nella tua cartella language/, ad esempio: {L_<em>&lt;STRINGNAME&gt;</em>} dove <em>&lt;STRINGNAME&gt;</em> è il nome della stringa tradotta che vuoi aggiungere. Per esempio, {L_WROTE} verrà visualizzata come "ha scritto" o la relativa traduzione della lingua in uso da parte dell\'utente.<br /><br />Usa il placeholder <em>&#37;s</em> per includere il tempo passato tra i messaggi uniti nel separatore.',
));
