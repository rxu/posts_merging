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
	'ACP_POSTS_MERGING'				=> 'Unione messaggi',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Qui è possibile modificare le impostazioni per l’estensione <em>Unione messaggi</em>.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Anteprima separatore',
	'MERGE_INTERVAL'				=> 'Intervallo unione messaggi',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Se un utente scrive più di due messaggi in questo lasso di tempo, i messaggi saranno uniti in uno; Le informazioni sull’ora di scrittura del messaggio saranno sovrascritte con quelle dell’ultimo messaggio inviato. Lasciare il campo vuoto (o a 0) per disabilitare l’unione dei messaggi.',
	'MERGE_NO_TOPICS'				=> 'Argomenti esclusi',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Quest’elenco di ID argomenti separati da virgola sarà escluso dalle impostazioni di unione messaggi se l’estensione è abilitata.',
	'MERGE_NO_FORUMS'				=> 'Forum esclusi',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'L’unione dei messaggi <strong>sarà disabilitata nei forum selezionati</strong>. Non selezionare alcun forum per usare le impostazioni di unione messaggi in tutti i forum.<br />Selezionare/Deselezionare più forum tenendo premuto il tasto <samp>CTRL</samp> e cliccando sui forum.',
	'MERGE_SEPARATOR'				=> 'Separatore',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Qui è possibile configurare il separatore che apparirà tra le parti unite del messaggio.<br />È possibile far uso di BBCode che saranno interpretati secondo le impostazioni messaggi della board.<br /><br />È anche possibile usare le linee di traduzione della propria cartella di lingua (es.: <samp>it/</samp>) così: {L_<em>&lt;STRINGNAME&gt;</em>} dove <em>&lt;STRINGNAME&gt;</em> è il nome della riga di traduzione che si vuole aggiungere.Per esempio, {L_WROTE} sarà mostrata come “wrote” o nella sua forma tradotta in base alla lingua impostata dall’utente.<br /><br />Usare (una volta) il segnaposto <em>&#37;s</em> per aggiungere il tempo passato tra le due unioni nel separatore.',
));
