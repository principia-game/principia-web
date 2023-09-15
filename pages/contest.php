<?php

$contestid = $_GET['id'] ?? 0;

$contest = fetch("SELECT * FROM contests WHERE id = ?", [$contestid]);

if (!$contest) error('404');

clearMentions('contest', $contestid);

$levels = query("SELECT ce.*, l.id id, l.title title, $userfields FROM contests_entries ce JOIN levels l ON ce.level = l.id JOIN users u ON l.author = u.id WHERE ce.contest = ? ORDER BY ce.ranking DESC, l.id DESC", [$contestid]);

$comments = query("SELECT c.*, $userfields FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 3 AND c.level = ? ORDER BY c.time DESC", [$contestid]);

twigloader()->display('contest.twig', [
	'contestid' => $contestid,
	'contest' => $contest,
	'levels' => fetchArray($levels),
	'comments' => $comments
]);
