<?php
require('lib/common.php');

$contestdata = query("SELECT id,title FROM contests ORDER BY id DESC");

echo twigloader()->render('contests.twig', [
	'contests' => fetchArray($contestdata)
]);