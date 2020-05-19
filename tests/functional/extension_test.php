<?php
/**
*
* List subforums in columns extension for the phpBB Forum Software package.
*
* @copyright (c) 2020 phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace rxu\listsubforumsincolumns\tests\functional;

/**
 * @group functional
 */
class extension_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return array('rxu/listsubforumsincolumns');
	}

	public function test_forum_setting()
	{
		$this->login();
		$this->admin_login();

		$this->add_lang('acp/forums');
		$this->add_lang_ext('rxu/listsubforumsincolumns', 'info_acp_sflist');
		
		$crawler = self::request('GET', "adm/index.php?i=acp_forums&icat=7&mode=manage&parent_id=1&f=2&action=edit&sid={$this->sid}");
		$this->assertContains($this->lang('SUBFORUMSLIST_TYPE'), $crawler->filter('dt > label[for="subforumslist_type"]')->text());
		$this->assertContains('0', $crawler->filter('dd > input[name="subforumslist_type"]')->attr('value'));

		$form = $crawler->selectButton('update')->form([
			'subforumslist_type'	=> 1,
		]);
		$crawler = self::submit($form);
		$this->assertContains($this->lang('FORUM_UPDATED'), $crawler->filter('.successbox')->text());

		$crawler = self::request('GET', "adm/index.php?i=acp_forums&icat=7&mode=manage&parent_id=1&f=2&action=edit&sid={$this->sid}");
		$this->assertContains($this->lang('SUBFORUMSLIST_TYPE'), $crawler->filter('dt > label[for="subforumslist_type"]')->text());
		$this->assertContains('1', $crawler->filter('dd > input[name="subforumslist_type"]')->attr('value'));
	}

	public function test_subforums_in_columns_enabled()
	{
		$this->login();
		$this->admin_login();

		$this->add_lang('acp/forums');
		$this->add_lang_ext('rxu/listsubforumsincolumns', 'info_acp_sflist');

		$forum_names = ['Subforum #1', 'Subforum #2', 'Subforum #3'];
		foreach ($forum_names as $forum_name)
		{
			$crawler = self::request('GET', "adm/index.php?i=acp_forums&mode=manage&parent_id=2&sid={$this->sid}");
			$crawler = self::submit($crawler->selectButton('addforum')->form());
			$form = $crawler->selectButton('update')->form([
				'forum_name'		=> $forum_name,
				'forum_parent_id'	=> 2,
				'forum_perm_from'	=> 2,
			]);
			$crawler = self::submit($form);
			$this->assertContains($this->lang('FORUM_CREATED'), $crawler->filter('.successbox')->text());
		}

		$crawler = self::request('GET', "index.php?sid={$this->sid}");

		$this->assertContains('Subforum #1', $crawler->filter('span[class="list_subforums_in_columns"]')->filter('a[class="subforum read"]')->eq(0)->text());
		$this->assertContains('Subforum #2', $crawler->filter('span[class="list_subforums_in_columns"]')->filter('a[class="subforum read"]')->eq(1)->text());
		$this->assertContains('Subforum #3', $crawler->filter('span[class="list_subforums_in_columns"]')->filter('a[class="subforum read"]')->eq(2)->text());
	}

	public function test_subforums_in_columns_disabled()
	{
		$this->login();
		$this->admin_login();

		$this->add_lang('acp/forums');
		$this->add_lang_ext('rxu/listsubforumsincolumns', 'info_acp_sflist');

		$crawler = self::request('GET', "adm/index.php?i=acp_forums&icat=7&mode=manage&parent_id=1&f=2&action=edit&sid={$this->sid}");
		$this->assertContains($this->lang('SUBFORUMSLIST_TYPE'), $crawler->filter('dt > label[for="subforumslist_type"]')->text());
		$this->assertContains('1', $crawler->filter('dd > input[name="subforumslist_type"]')->attr('value'));

		$form = $crawler->selectButton('update')->form([
			'subforumslist_type'	=> 0,
		]);
		$crawler = self::submit($form);
		$this->assertContains($this->lang('FORUM_UPDATED'), $crawler->filter('.successbox')->text());

		$crawler = self::request('GET', "index.php?sid={$this->sid}");
		$this->assertContains('Subforum #1', $crawler->filter('div[class="list-inner"]')->filter('a[class="subforum read"]')->eq(0)->text());
		$this->assertContains('Subforum #2', $crawler->filter('div[class="list-inner"]')->filter('a[class="subforum read"]')->eq(1)->text());
		$this->assertContains('Subforum #3', $crawler->filter('div[class="list-inner"]')->filter('a[class="subforum read"]')->eq(2)->text());
	}

}
