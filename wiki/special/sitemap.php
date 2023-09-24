<?php
$sitemap = new Sitemap('https://principia-web.se/');

$pages = glob(WIKI_PAGES.'*.md');

foreach ($pages as $page)
	$sitemap->add('wiki/'.filepathToSlug($page));

$sitemap->output();
