<?php
require('lib/common.php');
pageheader();

$lid = (isset($_GET['id']) ? $_GET['id'] : 0);

$level = fetch("SELECT l.*, u.id u_id, u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

query("UPDATE levels SET views = views + '1' WHERE id = ?", [$lid]);
$level['views']++;

$bbCode = new \Genert\BBCode\BBCode();
$bbCode->addLinebreakParser();
$level['description'] = $bbCode->convertToHtml($level['description']);

// TODO: Implement disabling the edit button (from enabling/disabling derivates).
// TODO: Increment downloads.
$twig = twigloader();

echo $twig->render('level.php', [
	'lid' => $lid,
	'level' => $level,
	'bbCode' => $bbCode
]);

pagefooter();