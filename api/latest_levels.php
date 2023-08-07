<?php
chdir('../');
require('lib/common.php');

$limit = clamp($_GET['limit'] ?? 5, 0, 50);
$offset = (int)($_GET['offset'] ?? 0);

$levels = fetchArray(query("SELECT l.id id, l.title title, $userfields FROM levels l JOIN users u ON l.author = u.id ORDER BY l.id DESC LIMIT ?,?",
	[$offset, $limit]));

header('Content-Type: application/json');

if (!$levels) die(json_encode([ 'error' => 'No levels (limit 0 or too large offset?)' ]));

echo(json_encode($levels));
