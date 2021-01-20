<?php
require('lib/common.php');

$newsdata = query("SELECT * FROM news ORDER BY id DESC LIMIT 5");

$latestquery = "SELECT $userfields l.id id,l.title title,l.locked locked FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d AND l.locked = 0 ORDER BY l.id DESC LIMIT 4";
$latestcustom = query(sprintf($latestquery, 1));
$latestadvent = query(sprintf($latestquery, 2));

$justRegistered = (isset($_GET['rd']) ? true : false);

$twig = twigloader();

echo $twig->render('index.twig', [
	'just_registered' => $justRegistered,
	'news' => fetchArray($newsdata),
	'custom_levels' => fetchArray($latestcustom),
	'adventure_levels' => fetchArray($latestadvent)
]);