<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Translated By : Bassel Taha Alhitary - www.alhitary.net
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
	'ACP_POSTS_MERGING'				=> 'دمج المشاركات ',
	'ACP_POSTS_MERGING_EXPLAIN'		=> 'من هنا تستطيع ضبط الإعدادات لهذه الإضافة.',
	'ACP_POSTS_MERGING_SEPARATOR_PREVIEW'	=> 'استعراض ',
	'MERGE_INTERVAL'				=> 'الفترة ',
	'MERGE_INTERVAL_EXPLAIN'		=> 'سيتم دمج المُشاركات إلى مُشاركة واحدة في حالة أن العضو أضاف أكثر من 2 مُشاركات خلال هذه الفترة التي تحددها هُنا. سيتم أيضاً إضافة المعلومات حول الوقت الذي اُستغرق منذ المُشاركة السابقة ( كُل مُشاركة على حدة ). اتركه فارغاً أو اكتب القيمة صفر لتعطيل عملية دمج المُشاركات.',
	'MERGE_NO_TOPICS'				=> 'استبعاد المواضيع ',
	'MERGE_NO_TOPICS_EXPLAIN'		=> 'اكتب العلامة الفاصلة , بين أرقام المواضيع التي لا تريد دمج المُشاركات فيها.',
	'MERGE_NO_FORUMS'				=> 'استبعاد المنتديات ',
	'MERGE_NO_FORUMS_EXPLAIN'		=> 'عملية دمج المُشاركات <strong>لن تعمل في المنتديات التي تختارها هنا</strong>. سيتم دمج المُشاركات في جميع المنتديات في حالة عدم اختيارك لأي منتدى.<br />تحديد/إلغاء التحديد لأكثر من منتدى يكون بواسطة النقر مُطولاً على زر الكنترول <samp>CTRL</samp> والنقر بالفارة ( الماوس ) على المنتديات الذي تريدها.',
	'MERGE_SEPARATOR'				=> 'الفاصل ',
	'MERGE_SEPARATOR_EXPLAIN'		=> 'من هنا تستطيع إعداد الفاصل الذي سيظهر بين المُشاركات التي تم دمجها.<br />تستطيع استخدام أكواد الكتابة BBCodes التي سيتم تحليلها بحسب إعدادات المنتدى أو المُشاركة.<br /><br />تستطيع أيضاً استخدام أي سلسلة لغة موجودة في مجلد اللغة لديك مثل هذا : {L_<em>&lt;STRINGNAME&gt;</em>} حيث أن الـ <em>&lt;STRINGNAME&gt;</em> هو إسم السلسلة المُترجمة التي تريد إضافتها. على سبيل المثال : {L_WROTE} سوف تظهر كـ “كتب” أو الكلمة المُترجمة بحسب اللغة المُستخدمة بواسطة العضو.<br /><br />استخدم العنصر <em>{TIME}</em> ( مرة واحدة ) لإضافة الوقت الذي مر بين ا.',
]);
