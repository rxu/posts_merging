<?php
/**
 *
 * Posts Merging extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, rxu, https://www.phpbbguru.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace rxu\postsmerging\tests\functional;

/**
 * @group functional
 */
class posts_merging_acp_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return ['rxu/postsmerging'];
	}

	public function test_acp_module()
	{
		$this->login();
		$this->admin_login();

		$this->add_lang_ext('rxu/postsmerging', 'info_acp_posts_merging');

		$crawler = self::request('GET', "adm/index.php?sid={$this->sid}&i=-rxu-postsmerging-acp-posts_merging_module&mode=config_posts_merging");
		$this->assertContainsLang('ACP_POSTS_MERGING', $crawler->filter('div[class="main"] > h1')->text());
		$this->assertContainsLang('ACP_POSTS_MERGING_EXPLAIN', $crawler->filter('div[class="main"] > p')->text());

		$form = $crawler->selectButton('Preview')->form();
		$crawler = self::submit($form);
		$this->assertContainsLang('ACP_POSTS_MERGING_SEPARATOR_PREVIEW', $crawler->filter('fieldset > fieldset > legend')->text());

		$form = $crawler->selectButton('Submit')->form();
		$crawler = self::submit($form);
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->filter('div[class="successbox"] > p')->text());
	}

	public function test_changing_merge_separator()
	{
		$this->login();
		$this->admin_login();

		$this->add_lang_ext('rxu/postsmerging', 'info_acp_posts_merging');

		$crawler = self::request('GET', "adm/index.php?sid={$this->sid}&i=-rxu-postsmerging-acp-posts_merging_module&mode=config_posts_merging");
		$this->assertContainsLang('ACP_POSTS_MERGING', $crawler->filter('div[class="main"] > h1')->text());
		$this->assertContainsLang('ACP_POSTS_MERGING_EXPLAIN', $crawler->filter('div[class="main"] > p')->text());

		$form = $crawler->selectButton('Preview')->form([
			'posts_merging_separator_text' => 'Merged after the time: {TIME}',
		]);
		$crawler = self::submit($form);
		$this->assertStringContainsString('Merged after the time: 3 hours 17 minutes 56 seconds', $crawler->filter('fieldset > fieldset > p')->text());

		// test saving new separator
		$form = $crawler->selectButton('Submit')->form();
		$crawler = self::submit($form);
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->filter('div[class="successbox"] > p')->text());
		$crawler = self::request('GET', "adm/index.php?sid={$this->sid}&i=-rxu-postsmerging-acp-posts_merging_module&mode=config_posts_merging");
		$this->assertStringContainsString('Merged after the time: {TIME}', $crawler->filter('textarea')->text());

		// Revert separator back to default {L_MERGE_SEPARATOR}
		$form = $crawler->selectButton('Submit')->form([
			'posts_merging_separator_text' => '{L_MERGE_SEPARATOR}',
		]);
		$crawler = self::submit($form);
		$this->assertContainsLang('CONFIG_UPDATED', $crawler->filter('div[class="successbox"] > p')->text());
		$crawler = self::request('GET', "adm/index.php?sid={$this->sid}&i=-rxu-postsmerging-acp-posts_merging_module&mode=config_posts_merging");
		$this->assertStringContainsString('{L_MERGE_SEPARATOR}', $crawler->filter('textarea')->text());
	}
}
