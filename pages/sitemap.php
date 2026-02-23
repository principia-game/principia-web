<?php

$s = new Sitemap('https://principia-web.se/');

if (IS_ARCHIVE) {
	$s->setPrefix('archive/');

	$s->add('');

	$contests = query("SELECT id FROM archive_contests");
	while ($contest = $contests->fetch())
		$s->add('contest/'.$contest['id']);

	$s->add(['levellist']);

	$levels = query("SELECT id FROM archive_levels WHERE visibility = 0");
	while ($level = $levels->fetch())
		$s->add('level/'.$level['id']);

	$s->add(['random']);

	$users = query("SELECT id FROM archive_users ORDER BY id");
	while ($user = $users->fetch())
		$s->add('user/'.$user['id']);

	$s->add('userlist');

	$s->output();
	return;
}

$s = new Sitemap('https://principia-web.se/');
$s->add(['', 'about', 'browse', 'classic-puzzles', 'credits']);

$contests = query("SELECT id FROM contests");
while ($contest = $contests->fetch()) {
	$s->add('contest/'.$contest['id']);
}

$s->add(['contests', 'donate', 'download', 'featured', 'forgotpassword', 'image-to-lua']);

$s->add(['levels', 'levels?type=custom', 'levels?type=adventure', 'levels?type=puzzle']);
$s->add(['levels?sort=old', 'levels?sort=top', 'levels?sort=pop']);
$s->add(['levels?archive=1', 'levels?archive=1&type=custom', 'levels?archive=1&type=adventure', 'levels?archive=1&type=puzzle']);

$s->add(['levellist']);

$levels = query("SELECT id FROM levels WHERE visibility = 0");
while ($level = $levels->fetch())
	$s->add('level/'.$level['id']);

$s->add(['listpackages', 'login', 'news', 'news/feed']);

$latestnews = News::getLatestArticle();
for ($i = 1; $i < $latestnews['id']+1; $i++)
	$s->add('news/'.$i);

$packages = query("SELECT id FROM packages");
while ($package = $packages->fetch())
	$s->add('package/'.$package['id']);

$s->add(['privacy', 'random', 'register', 'rules', 'search', 'sitemaps']);

$users = query("SELECT id FROM users ORDER BY id");
while ($user = $users->fetch())
	$s->add('user/'.$user['id']);

$s->add('userlist');

$s->add('videos');
$videos = query("SELECT youtube_id FROM videos");
while ($video = $videos->fetch())
	$s->add('video/'.$video['youtube_id']);

$s->output();
