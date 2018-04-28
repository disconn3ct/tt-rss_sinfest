<?php
class Af_Sinfest extends Plugin {

	private $host;
	private $filters = array();

	function about() {
		return array(1.0,
			"Generates an RSS feed for Sinfest",
			"disconn3ct");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_FETCH_FEED, $this);
		$host->add_hook($host::HOOK_SUBSCRIBE_FEED, $this);
	}

	// SinFest hasn't updated their feed since 2014 (?!?) so generate one
	// Based off the GoComics example
	/**
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	function hook_fetch_feed($feed_data, $fetch_url, $owner_uid, $feed, $last_article_timestamp, $auth_login, $auth_pass) {
		if ($auth_login || $auth_pass)
			return $feed_data;

		if (preg_match('#^https?://(?:sinfest\.net)#i', $fetch_url)) {
			$site_url = 'http://sinfest.net/';

			$article_link = $site_url . 'view.php?date=' . date('Y-m-d');

			$body = fetch_file_contents(array('url' => $article_link, 'type' => 'text/html', 'followlocation' => false));

			require_once 'lib/MiniTemplator.class.php';

			$feed_title = htmlspecialchars('Sinfest');
			$site_url = htmlspecialchars($site_url);
			$article_link = htmlspecialchars($article_link);

			$tpl = new MiniTemplator();

			$tpl->readTemplateFromFile('templates/generated_feed.txt');

			$tpl->setVariable('FEED_TITLE', $feed_title, true);
			$tpl->setVariable('VERSION', VERSION, true);
			$tpl->setVariable('FEED_URL', htmlspecialchars($fetch_url), true);
			$tpl->setVariable('SELF_URL', $site_url, true);

			$tpl->setVariable('ARTICLE_UPDATED_ATOM', date('c'), true);
			$tpl->setVariable('ARTICLE_UPDATED_RFC822', date(DATE_RFC822), true);
			$tpl->setVariable('ARTICLE_ID', $article_link, true);
			$tpl->setVariable('ARTICLE_LINK', $article_link, true);
			$tpl->setVariable('ARTICLE_TITLE', date('l, F d, Y'), true);
			$tpl->setVariable('ARTICLE_EXCERPT', '', true);
			$tpl->setVariable('ARTICLE_CONTENT', '<img src="http://sinfest.net/btphp/comics/' . date('Y-m-d') . '.gif">');

			$tpl->setVariable('ARTICLE_AUTHOR', '', true);
			$tpl->setVariable('ARTICLE_SOURCE_LINK', $site_url, true);
			$tpl->setVariable('ARTICLE_SOURCE_TITLE', $feed_title, true);

			$tpl->addBlock('entry');

			$tpl->addBlock('feed');

			$tmp_data = '';

			if ($tpl->generateOutputToString($tmp_data))
				$feed_data = $tmp_data;
		}

		return $feed_data;
	}

	function hook_subscribe_feed($contents, $url, $auth_login, $auth_pass) {
		if ($auth_login || $auth_pass)
			return $contents;

		if (preg_match('#^http?://(?:sinfest\.net)#i', $url))
			return '<?xml version="1.0" encoding="utf-8"?>'; // Get is_html() to return false.

		return $contents;
	}

	function api_version() {
		return 2;
	}

}
