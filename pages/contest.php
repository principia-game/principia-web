<?php

$contestid = (int)($path[2] ?? 0);

$contest = fetch("SELECT * FROM contests WHERE id = ?", [$contestid]);

if (!$contest) error('404');

if (strtotime($contest['time_from']) > time() && !IS_ADMIN)
	error('403', "This contest has not started yet. No peeking!");

clearMentions('contest', $contestid);

$levels = query("SELECT ce.*, l.id id, l.title title, $userfields FROM contests_entries ce JOIN levels l ON ce.level = l.id JOIN users u ON l.author = u.id WHERE ce.contest = ? ORDER BY ce.ranking DESC, l.id DESC", [$contestid]);

twigloader()->display('contest.twig', [
	'contestid' => $contestid,
	'contest' => $contest,
	'levels' => fetchArray($levels),
	'comments' => getComments('contest', $contestid)
]);
