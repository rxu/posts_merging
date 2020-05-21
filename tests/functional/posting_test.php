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
class posts_merging_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('rxu/postsmerging');
	}

	public function test_merging_posts()
	{
		$this->login();

		// Create a topic
		$post = $this->create_topic(2, 'Test Topic 1', 'This is a first test topic posted by the testing framework.');

		// Now test actual posts merging
		$crawler = self::request('GET', "posting.php?mode=reply&f=2&t={$post['topic_id']}&sid={$this->sid}");
		$form = $crawler->selectButton('Submit')->form();
		$form->setValues(array('message' => 'This is a post which SHOULD BE merged with the previous one.'));
		$crawler = self::submit($form);

		$this->assertContains('Added in', $crawler->filter('html')->text());
	}

	public function test_ignore_merging_posts()
	{
		$this->login();

		// Create a topic
		$post = $this->create_topic(2, 'Test Topic 2', 'This is a second test topic posted by the testing framework.');

		// Test the ignore option checkbox is present
		$crawler = self::request('GET', "posting.php?mode=reply&f=2&t={$post['topic_id']}&sid={$this->sid}");
		$this->assertContains('Do not merge with previous post', $crawler->filter('html')->text());

		// Test option to ignore merging posts
		$post2 = $this->create_post(2, $post['topic_id'], 'Re: Test Topic 2', 'This is a post which should NOT be merged with the previous one.', array('posts_merging_option' => true));

		$crawler = self::request('GET', "viewtopic.php?t={$post2['topic_id']}&sid={$this->sid}");
		$this->assertNotContains('Added in', $crawler->filter('html')->text());
	}
}
