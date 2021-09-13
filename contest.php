<?php
require('lib/common.php');

$contestid = (isset($_GET['id']) ? $_GET['id'] : 0);

$contest = fetch("SELECT * FROM contests WHERE id = ?", [$contestid]);

if (!$contest) {
	error('404', "The requested contest wasn't found.");
}

clearMentions('contest', $contestid);

$markdown = new Parsedown();
$contest['description'] = $markdown->text($contest['description']);

$levels = query("SELECT $userfields ce.*, l.id id,l.title title FROM contests_entries ce JOIN levels l ON ce.level = l.id JOIN users u ON l.author = u.id WHERE ce.contest = ? ORDER BY ce.ranking DESC, l.id DESC", [$contestid]);

$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 3 AND c.level = ? ORDER BY c.time DESC", [$contestid]);

$twig = twigloader();
echo $twig->render('contest.twig', [
	'contestid' => $contestid,
	'contest' => $contest,
	'levels' => fetchArray($levels),
	'comments' => $comments
]);