<?php
$sitemap = new Sitemap('https://principia-web.se/');

$pages = query("SELECT title FROM wikipages ORDER BY title ASC");

foreach ($pages as $page)
	$sitemap->add('wiki/'.str_replace(' ', '_', $page['title']));

$sitemap->output();
