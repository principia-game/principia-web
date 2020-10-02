<?php
require('lib/common.php');

$newsdata = query("SELECT * FROM news ORDER BY id DESC LIMIT 5");

$latestquery = "SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d ORDER BY l.id DESC LIMIT 5";
$latestcustom = query(sprintf($latestquery, 1));
$latestadvent = query(sprintf($latestquery, 2));

$justRegistered = (isset($_GET['rd']) ? true : false);

$twig = twigloader();

echo $twig->render('index.php', [
	'just_registered' => $justRegistered,
	'news' => fetchArray($newsdata),
	'custom_levels' => fetchArray($latestcustom),
	'adventure_levels' => fetchArray($latestadvent)
]);