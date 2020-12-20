<?php
require('lib/common.php');

$contestdata = query("SELECT id,title FROM contests ORDER BY id DESC");

$twig = twigloader();
echo $twig->render('contests.twig', [
	'contests' => fetchArray($contestdata)
]);