<?php
require('lib/common.php');

$lid = $_GET['id'] ?? 0;

$level = fetch("SELECT $userfields l.* FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

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
			$cachectrl->invIndexTop();
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
		$vis = ($level['visibility'] == 1 ? 0 : 1);

		query("UPDATE levels SET visibility = ? WHERE id = ?", [$vis, $lid]);
		$level['visibility'] = $vis;

		$cachectrl->invLevelCount($level['author']);
	}

	// rerun webhook
	if (isset($_GET['rerunhook']) && $userdata['powerlevel'] > 2) {
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
	if (isset($_GET['delthumb']) && $userdata['powerlevel'] > 2) {
		unlink("levels/thumbs/$lid.jpg");
		unlink("levels/thumbs/low/$lid.jpg");
	}

	// remove notifications
	query("DELETE FROM notifications WHERE type = 1 AND level = ? AND recipient = ?", [$level['id'], $userdata['id']]);
	clearMentions('level', $level['id']);
}

if (!isset($hasLiked)) $hasLiked = false;

if ($log) {
	query("UPDATE levels SET views = views + '1' WHERE id = ?", [$lid]);
	$level['views']++;
}

$contests = query("SELECT id,title,active FROM contests WHERE active = 1");

$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 1 AND c.level = ? ORDER BY c.time DESC", [$lid]);

$derivatives = query("SELECT $userfields l.id id,l.title title FROM levels l JOIN users u ON l.author = u.id WHERE l.parent = ? AND l.visibility = 0 ORDER BY l.id DESC LIMIT 4", [$lid]);
if ($level['parent']) {
	$parentLevel = fetch("SELECT $userfields l.id id,l.title title FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ? AND l.visibility = 0", [$level['parent']]);
}

$twig = twigloader();

echo $twig->render('level.twig', [
	'lid' => $lid,
	'level' => $level,
	'has_liked' => $hasLiked,
	'contests' => fetchArray($contests),
	'contest_entered' => $contestEntered ?? null,
	'already_entered' => $alreadyEntered ?? false,
	'comments' => fetchArray($comments),
	'derivatives' => fetchArray($derivatives),
	'parentlevel' => $parentLevel ?? null
]);
