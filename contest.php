<?php
require('lib/common.php');

$contestid = (isset($_GET['id']) ? $_GET['id'] : 0);

$contest = fetch("SELECT * FROM contests WHERE id = ?", [$contestid]);

$bbCode = new \Genert\BBCode\BBCode();
$bbCode->addLinebreakParser();
$contest['description'] = $bbCode->convertToHtml($contest['description']);

/*
$entries = query("SELECT * FROM contests_entries WHERE contest = ?", [$contestid]);
$contestLevels = [];
foreach ($entries as $entry) {
	$contestLevels[] = $entry['level'];
}
$contestLevels = implode("','", $contestLevels);
// This query is safe from injection since the data is retrieved from database and is known to be clean integers.
$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.id IN ('$contestLevels') ORDER BY l.id DESC");
*/

$levels = query("SELECT ce.*, l.id id,l.title title,u.id u_id,u.name u_name FROM contests_entries ce JOIN levels l ON ce.level = l.id JOIN users u ON l.author = u.id WHERE ce.contest = ? ORDER BY ce.ranking DESC, l.id DESC", [$contestid]);

$comments = query("SELECT c.*,u.id u_id,u.name u_name FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 3 AND c.level = ? ORDER BY c.time DESC", [$contestid]);

$twig = twigloader();
echo $twig->render('contest.twig', [
	'contestid' => $contestid,
	'contest' => $contest,
	'levels' => fetchArray($levels),
	'comments' => $comments
]);