<?php

if (isset($_GET['id'])) redirect('/level/'.$_GET['id']);

$lid = $path[2] ?? 0;

// HACK: Fake level ID to take the user to the download page (for old principia-web mod)
if ($lid == 2147483000) redirect('https://principia-web.se/download');

$level = fetch("SELECT l.*, $userfields FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

if (!$level) error('404', "The requested level wasn't found.");

assert($lid == $level['id']);

if ($log) {
	// like
	$hasLiked = result("SELECT COUNT(*) FROM likes WHERE user = ? AND level = ?", [$userdata['id'], $lid]) == 1 ? true : false;
	if (isset($_POST['vote'])) {
		if (!$hasLiked) {
			query("UPDATE levels SET likes = likes + '1' WHERE id = ?", [$lid]);

			insertInto('likes', ['user' => $userdata['id'], 'level' => $lid]);
			$cachectrl->invIndexTop();
		}
		die();
	}

	// add to contest
	if (isset($_POST['addtocontest'])) {
		$contestEntered = result("SELECT title FROM contests WHERE id = ?", [$_POST['addtocontest']]);
		$alreadyEntered = (result("SELECT COUNT(*) FROM contests_entries WHERE contest = ? AND level = ?", [$_POST['addtocontest'], $lid]) ? true : false);
		if ($contestEntered && !$alreadyEntered)
			insertInto('contests_entries', ['contest' => $_POST['addtocontest'], 'level' => $lid]);
	}

	// toggle lock
	if (isset($_GET['togglelock']) && ($level['author'] == $userdata['id'] || $userdata['rank'] > 1)) {
		$vis = ($level['visibility'] == 1 ? 0 : 1);

		query("UPDATE levels SET visibility = ? WHERE id = ?", [$vis, $lid]);
		$level['visibility'] = $vis;

		$cachectrl->invLevelCount($level['author']);
		$cachectrl->invIndex();
	}

	// rerun webhook
	if (isset($_GET['rerunhook']) && $userdata['rank'] > 2) {
		$webhookdata = [
			'id' => $level['id'],
			'name' => $level['title'],
			'description' => $level['description'],
			'u_id' => $level['u_id'],
			'u_name' => $level['u_name']
		];

		newLevelHook($webhookdata);
	}

	// delete level thumbnails
	if (isset($_GET['delthumb']) && $userdata['rank'] > 2) {
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

$contests = query("SELECT id, title, active FROM contests WHERE active = 1");

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

echo twigloader()->render('level.twig', [
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
