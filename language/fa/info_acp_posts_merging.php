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
	'ACP_POSTS_MERGING'				=> 'پیوند ارسالها',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'اینجا مکانی است برای اعمال تنظیمات بر روی افزونه پیوند ارسالها',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'Separator preview',
	'MERGE_INTERVAL'				=> 'فاصله پیوند ارسالها',
	'MERGE_INTERVAL_EXPLAIN'		=> 'در صورت ارسال 2 نوشته در این بازه زمانی هر دو به یک ارسال پیوند خواهند شد. اطلاعات زمانی مربوط به زمان نوشته ارسال شده قبلی نمایش داده خواهد شد(برای هر دو ارسال). برای غیرفعال سازی پیوند ارسال 0 یا خالی بگذارید.',
	'MERGE_NO_TOPICS'				=> 'موضوع های جداشده',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'شناسه موضوع هایی که نباید پیوند ارسال در زمان فعال بودن در آنها انجام گیرد با علامت , (کاما) از هم جدا کنید.',
	'MERGE_NO_FORUMS'				=> 'انجمن های جداشده',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'شناسه انجمن هایی که نباید پیوند ارسال در زمان فعال بودن در آنها انجام گیرد با علامت , (کاما) از هم جدا کنید.',
	'MERGE_SEPARATOR'				=> 'Separator',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'Here you can configure the separator which will appear between the merged message parts.<br />You can use BBCodes which will be parsed in according to the board or message settings.<br /><br />You can also use any language string present in your language/ directory like this: {L_<em>&lt;STRINGNAME&gt;</em>} where <em>&lt;STRINGNAME&gt;</em> is the name of the translated string you want to add. For example, {L_WROTE} will be displayed as “wrote” or its translation according to user’s locale.<br /><br />Use <em>&#37;s</em> placeholder (once) to include the time passed between merging in the separator.',
]);
