<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
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

$lang = array_merge($lang, [
	'ACP_POSTS_MERGING'				=> 'Fusionar mensajes',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Aquí puede aplicar los ajustes para la extensión Fusionar mensajes.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Vista previa de separador',
	'MERGE_INTERVAL'				=> 'Intervalo de fusión de mensajes',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Si un usuario envía más de 2 mensajes en este período de tiempo, los mensajes se fusionarán en un solo mensaje. La información sobre el tiempo transcurrido desde el mensaje anterior se añadirá (para cada mensaje). Dejar en blanco o establecer 0 para desactivar la Fusión de mensajes.',
	'MERGE_NO_TOPICS'				=> 'Temas excluidos',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Lista separados por coma de IDs de los temas donde la fusión de mensajes no se debe aplicar, cuando la función está activada.',
	'MERGE_NO_FORUMS'				=> 'Foros excluidos',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'Lista separados por coma de IDs de los foros donde la fusión de mensajes no se debe aplicar, cuando la función está activada.',
	'MERGE_SEPARATOR'				=> 'Separador',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Aquí puede configurar el separador que aparecerá entre las partes del mensaje fusionado.<br />Puede usar BBCodes que se analizará en función de la configuración del foro o del mensaje.<br /><br />También puede usar cualquier cadena de idioma presente en su directorio de language/ como esta: {L_<em>&lt;STRINGNAME&gt;</em>} dónde <em>&lt;STRINGNAME&gt;</em> es el nombre de la cadena traducida que desea agregar. Por ejemplo, {L_WROTE} se mostrará como “escribió” o su traducción de acuerdo con la configuración regional del usuario.<br /><br />Use <em>&#37;s</em> marcador de posición (una vez) para incluir el tiempo transcurrido entre la fusión en el separador.',
]);
