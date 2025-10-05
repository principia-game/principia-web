<?php
if (IS_ARCHIVE) {
	$page = $_GET['page'] ?? 1;

	$sitemap = new Sitemap('https://archive.principia-web.se/');

	$levels = query("SELECT id FROM @levels WHERE visibility = 0".paginate($page, 5000));
	while ($level = $levels->fetch()) {
		$sitemap->add('level/'.$level['id']);
	}

	$sitemap->output();

	return;
}

$sitemap = new Sitemap('https://principia-web.se/');

$sitemap->add('');
$sitemap->add('about');
$sitemap->add('browse');
$sitemap->add('classic-puzzles');
$sitemap->add('credits');

$contests = query("SELECT id FROM contests");
while ($contest = $contests->fetch()) {
	$sitemap->add('contest/'.$contest['id']);
}

$sitemap->add('contests');
$sitemap->add('donate');
$sitemap->add('forgotpassword');

$levels = query("SELECT id FROM levels WHERE visibility = 0");
while ($level = $levels->fetch()) {
	$sitemap->add('level/'.$level['id']);
}

$sitemap->add('news');

$latestnews = News::getLatestArticle();
for ($i = 1; $i < $latestnews['id']+1; $i++) {
	$sitemap->add('news/'.$i);
}

$sitemap->add('privacy');
$sitemap->add('rules');

$users = query("SELECT id FROM users ORDER BY id");
while ($user = $users->fetch()) {
	$sitemap->add('user/'.$user['id']);
}

$sitemap->add('userlist');

$sitemap->output();
