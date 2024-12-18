<?php

if (isset($_GET['id'])) redirect('/level/%d', $_GET['id']);

$lid = (int)($path[2] ?? 0);

$level = fetch("SELECT l.*, $userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

if (!$level) error('404');

assert($lid == $level['id']);

if ($log) {
	// like
	$hasLiked = result("SELECT COUNT(*) FROM likes WHERE user = ? AND level = ?", [$userdata['id'], $lid]) == 1 ? true : false;
	if (isset($_POST['vote'])) {
		if (!$hasLiked)
			likeLevel($lid, $userdata['id']);

		die();
	}

	// add to contest
	if (isset($_POST['addtocontest'])) {
		$contestEntered = fetch("SELECT title, time_from, time_to FROM contests WHERE id = ?", [$_POST['addtocontest']]);

		// Check that the contest is actually current
		if (strtotime($contestEntered['time_from']) < time() && time() < strtotime($contestEntered['time_to'])) {
			$alreadyEntered = (result("SELECT COUNT(*) FROM contests_entries WHERE contest = ? AND level = ?", [$_POST['addtocontest'], $lid]) ? true : false);

			if ($contestEntered && !$alreadyEntered)
				insertInto('contests_entries', ['contest' => $_POST['addtocontest'], 'level' => $lid]);
		}
	}

	// toggle lock
	if (isset($_GET['togglelock']) && ($level['author'] == $userdata['id'] || IS_MOD)) {
		$level['visibility'] = toggleLevelLock($level, $level['visibility']);
	}

	// rerun webhook
	if (isset($_GET['rerunhook']) && IS_ADMIN) {
		newLevelHook([
			'id' => $level['id'],
			'name' => $level['title'],
			'description' => $level['description'],
			'u_id' => $level['u_id'],
			'u_name' => $level['u_name']
		]);
	}

	// delete level thumbnails
	if (isset($_GET['delthumb']) && IS_ADMIN) {
		unlink("data/thumbs/$lid.jpg");
		unlink("data/thumbs_low/$lid.jpg");
	}

	// remove notifications
	query("DELETE FROM notifications WHERE type = 1 AND level = ? AND recipient = ?", [$lid, $userdata['id']]);
	clearMentions('level', $lid);
}

if ($log) {
	query("UPDATE levels SET views = views + '1' WHERE id = ?", [$lid]);
	$level['views']++;
}

$leaderboard = query("SELECT l.*, $userfields
		FROM leaderboard l JOIN users u ON l.user = u.id WHERE l.level = ?
		ORDER BY l.score DESC LIMIT 8",
	[$lid]);

$contests = query("SELECT id, title FROM contests WHERE NOW() BETWEEN time_from AND time_to");

$comments = query("SELECT c.*, $userfields
		FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 1 AND c.level = ?
		ORDER BY c.time DESC",
	[$lid]);

$derivatives = query("SELECT l.id id,l.title title, $userfields
		FROM levels l JOIN users u ON l.author = u.id WHERE l.parent = ? AND l.visibility = 0
		ORDER BY l.id DESC LIMIT 4",
	[$lid]);

if ($level['parent'])
	$parentLevel = fetch("SELECT l.id id, l.title title, $userfields
			FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ? AND l.visibility = 0",
		[$level['parent']]);

twigloader()->display('level.twig', [
	'lid' => $lid,
	'level' => $level,
	'has_liked' => $hasLiked ?? false,
	'contests' => fetchArray($contests),
	'contest_entered' => $contestEntered ?? null,
	'already_entered' => $alreadyEntered ?? false,
	'comments' => fetchArray($comments),
	'derivatives' => fetchArray($derivatives),
	'parentlevel' => $parentLevel ?? null,
	'leaderboard' => fetchArray($leaderboard)
]);
