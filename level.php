<?php
require('lib/common.php');

$lid = (isset($_GET['id']) ? $_GET['id'] : 0);

$level = fetch("SELECT l.*, u.id u_id, u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

if (!$level) {
	error('404', "The requested level wasn't found.");
}

if ($log) {
	// like
	$hasLiked = result("SELECT COUNT(*) FROM likes WHERE user = ? AND level = ?", [$userdata['id'], $lid]) == 1 ? true : false;
	if (isset($_GET['vote'])) {
		if (!$hasLiked) {
			query("UPDATE levels SET likes = likes + '1' WHERE id = ?", [$lid]);
			query("INSERT INTO likes VALUES (?,?)", [$userdata['id'], $lid]);
		}
		die();
	}

	// add to contest
	if (isset($_POST['addtocontest'])) {
		$contestEntered = result("SELECT title FROM contests WHERE id = ?", [$_POST['addtocontest']]);
		$alreadyEntered = (result("SELECT COUNT(*) FROM contests_entries WHERE contest = ? AND level = ?", [$_POST['addtocontest'], $lid]) ? true : false);
		if ($contestEntered && !$alreadyEntered) {
			query("INSERT INTO contests_entries (contest, level) VALUES (?, ?)", [$_POST['addtocontest'], $lid]);
		}
	}

	// toggle lock
	if (isset($_GET['togglelock']) && ($level['author'] == $userdata['id'] || $userdata['powerlevel'] > 1)) {
		$lock = ($level['locked'] ? 0 : 1);
		query("UPDATE levels SET locked = ? WHERE id = ?", [$lock, $lid]);
		$level['locked'] = $lock;
	}

	// remove notifications
	query("DELETE FROM notifications WHERE level = ? AND recipient = ?", [$level['id'], $userdata['id']]);
}

if (!isset($hasLiked)) $hasLiked = false;

query("UPDATE levels SET views = views + '1' WHERE id = ?", [$lid]);
$level['views']++;

$markdown = new Parsedown();
$level['description'] = $markdown->text($level['description']);

$contests = query("SELECT id,title,active FROM contests WHERE active = 1");

$comments = query("SELECT c.*,u.id u_id,u.name u_name FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 1 AND c.level = ? ORDER BY c.time DESC", [$lid]);

$twig = twigloader();

echo $twig->render('level.twig', [
	'lid' => $lid,
	'level' => $level,
	'has_liked' => $hasLiked,
	'contests' => fetchArray($contests),
	'contest_entered' => (isset($contestEntered) ? $contestEntered : null),
	'already_entered' => (isset($alreadyEntered) ? $alreadyEntered : false),
	'comments' => fetchArray($comments)
]);
