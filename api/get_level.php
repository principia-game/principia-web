<?php
chdir('../');
require('lib/common.php');

$lid = (int)($_GET['id'] ?? null);

$level = fetch("SELECT $userfields l.* FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

header('Content-Type: application/json');

if (!$level) die(json_encode(['error' => 'No level. (Invalid or missing ID argument?)']));

echo(json_encode($level));
