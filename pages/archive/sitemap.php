<?php

$page = $_GET['page'] ?? 1;

$sitemap = new Sitemap('https://archive.principia-web.se/');

$levels = query("SELECT id FROM levels WHERE visibility = 0".paginate($page, 5000));
while ($level = $levels->fetch()) {
	$sitemap->add('level/'.$level['id']);
}

$sitemap->output();
