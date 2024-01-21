<?php

class News {

	static private $news;

	private static function loadNews() {
		require_once('data/news/metadata.php');

		self::$news = $newsMetadata;
	}

	/**
	 * Retrieve a list of news articles with the newest article on top.
	 *
	 * $limit sets a limit to the X latest news articles.
	 */
	public static function retrieveList($limit) {
		if (!self::$news) self::loadNews();

		$data = [];

		for ($i = 0; $i < $limit; $i++) {
			$id = count(self::$news)-$i;

			if ($id < 1) break;

			$article = self::$news[$id];
			$article['id'] = $id;

			$data[] = $article;
		}

		return $data;
	}

	/**
	 *
	 */
	public static function getArticle($id) {
		if (!self::$news) self::loadNews();

		$data = self::$news[$id] ?? null;

		if (!$data) return null;

		$data['text'] = file_get_contents('data/news/'.$id.'.md');

		return $data;
	}
}
