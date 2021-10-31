<?php
require('lib/common.php');

// Cache all index page queries for 1 hour unless explicitly flushed.

$newsdata = $cache->hit('idx_news', function () {
	return fetchArray(query("SELECT * FROM news ORDER BY id DESC LIMIT 5"));
}, 60*60);

$latestquery = "SELECT $userfields l.id id,l.title title,l.locked locked FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d AND l.locked = 0 ORDER BY l.id DESC LIMIT 4";

$latestcustom = $cache->hit('idx_anp', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 1)));
}, 60*60);

$latestadvent = $cache->hit('idx_adv', function () use ($latestquery) {
	return fetchArray(query(sprintf($latestquery, 2)));
}, 60*60);

$justRegistered = (isset($_GET['rd']) ? true : false);

$twig = twigloader();

echo $twig->render('index.twig', [
	'just_registered' => $justRegistered,
	'news' => $newsdata,
	'custom_levels' => $latestcustom,
	'adventure_levels' => $latestadvent
]);
