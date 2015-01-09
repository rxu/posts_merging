<?php
/**
*
* Posts Merging extension for the phpBB Forum Software package.
*
* @copyright (c) 2013 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
 * Translated By : Basil Taha Alhitary - www.alhitary.net
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
	'ACP_POSTS_MERGING'				=> 'دمج المشاركات ',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'تستطيع هنا ضبط الإعدادات لهذه الإضافة.',
	'MERGE_INTERVAL'				=> 'الفترة الفاصلة للدمج ',
	'MERGE_INTERVAL_EXPLAIN'		=> 'سيتم دمج المشاركات إلى مشاركة واحدة في حالة أن العضو أضاف أكثر من 2 مواضيع حلال هذه الفترة. سيتم ذكر الوقت الذي استغرق منذ المشاركة السابقة ( لكل مشاركة على حدة ). القيمة صفر أو تركه فارغاً يعني تعطيل هذا الخيار.',
	'MERGE_NO_TOPICS'				=> 'استبعاد المواضيع ',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'اكتب العلامة الفاصلة ", " بين أرقام المواضيع التي لا تريد دمج المشاركات فيها.',
	'MERGE_NO_FORUMS'				=> 'استبعاد المنتديات ',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'اكتب العلامة الفاصلة ", " بين أرقام المنتديات التي لا تريد دمج المشاركات فيها.',
));
