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
	'ACP_POSTS_MERGING'				=> 'Склеивание сообщений',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'Здесь можно настроить параметры расширения для склеивания сообщений.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Предпросмотр разделителя',
	'MERGE_INTERVAL'				=> 'Интервал склеивания сообщений',
	'MERGE_INTERVAL_EXPLAIN'		=> 'Количество часов, в течение которого сообщения пользователя будут склеены с его последним сообщением темы. Оставьте поле пустым или установите 0 для отключения этой функции.',
	'MERGE_NO_TOPICS'				=> 'Темы без склеивания',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'Список разделённых запятыми номеров тем, в которых склеивание сообщений отключено.',
	'MERGE_NO_FORUMS'				=> 'Форумы без склеивания',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'В выбранных форумах склеивание сообщений будет отключено. Не выбирайте ничего, если хотите использовать склеивание сообщений во всех форумах. Чтобы выбрать несколько форумов, пользуйтесь мышью, удерживая кнопку <samp>CTRL</samp>.',
	'MERGE_SEPARATOR'				=> 'Разделитель',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Здесь можно настроить разделитель между частями склеенного сообщения.<br />Можно использовать BBCode, которые будут обработаны в соответствии с настройками конференции и конкретного сообщения.<br /><br />Можно использовать любые языковые переменные из языковых файлов. Например, {L_<em>&lt;STRINGNAME&gt;</em>}, где <em>&lt;STRINGNAME&gt;</em> — это имя переведённой строки, которую вы хотите добавить. Так, {L_WROTE} будет отображено как «писал(а)» или переведено в зависимости от выбранного пользователем языка.<br /><br />Используйте подстановку <em>&#37;s</em> (один раз) для отображения временного интервала между сообщениями в разделителе.',
));
