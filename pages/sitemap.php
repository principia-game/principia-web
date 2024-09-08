<?php
$sitemap = new Sitemap('https://principia-web.se/');

$sitemap->add('');
$sitemap->add('about');
$sitemap->add('credits');

$contests = query("SELECT id FROM contests");
while ($contest = $contests->fetch()) {
	$sitemap->add('contest/'.$contest['id']);
}

$sitemap->add('contests');
$sitemap->add('donate');

$levels = query("SELECT id FROM levels WHERE visibility = 0");
while ($level = $levels->fetch()) {
	$sitemap->add('level/'.$level['id']);
}

$sitemap->add('matrix');
$sitemap->add('news');

$latestnews = News::getLatestArticle();
for ($i = 1; $i < $latestnews['id']+1; $i++) {
	$sitemap->add('news/'.$i);
}

$sitemap->add('privacy');

$users = query("SELECT id FROM users ORDER BY id");
while ($user = $users->fetch()) {
	$sitemap->add('user/'.$user['id']);
}

$sitemap->output();
