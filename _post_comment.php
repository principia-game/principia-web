<?php
require('lib/common.php');

$id = isset($_POST['id']) ? $_POST['id'] : null;
$type = isset($_POST['t']) ? $_POST['t'] : null;
$message = isset($_POST['comment']) ? $_POST['comment'] : null;
$doDelete = isset($_POST['del']) ? true : false;
$cmntId = isset($_POST['cmnt']) ? $_POST['cmnt'] : false;

if (!$log) die('login pls');
if (!$type || !$id) die('params pls');

if (!$doDelete) {
	if (!$message) die('params pls');
	//if (result("SELECT COUNT(*) FROM levels WHERE id = ?", [$id]) != 1) die('valid level pls');

	// rate-limit commenting to 10 times every 30 minutes. it should be fair enough to not prevent legitimate use
	$recentCommentCount = result("SELECT COUNT(*) FROM comments WHERE author = ? AND time > ?", [$userdata['id'], time() - (30 * 60)]);
	if ($recentCommentCount > 10) {
		die('Please wait a while until commenting again.');
	}

	if (!$nType = cmtTypeToNum($type)) {
		die('valid type pls');
	}

	query("INSERT INTO comments (type, level, author, time, message) VALUES (?,?,?,?,?)",
		[$nType, $id, $userdata['id'], time(), $message]);

	switch ($type) {
		case 'level':
			$leveldata = fetch("SELECT id, author FROM levels WHERE id = ?", [$id]);

			if ($userdata['id'] == $leveldata['author']) break;

			query("INSERT INTO notifications (type, level, recipient, sender) VALUES (?,?,?,?)",
				[1, $leveldata['id'], $leveldata['author'], $userdata['id']]);
		break;
		case 'user':
			if ($userdata['id'] == $id) break;

			query("INSERT INTO notifications (type, recipient, sender) VALUES (?,?,?)",
				[2, $id, $userdata['id']]);
		break;
	}

	preg_match('/@([A-Za-z0-9_-]+)/', $message, $matches);
	if (isset($matches[1])) {
		$mentionedUser = result("SELECT id FROM users WHERE name = ?", [$matches[1]]);
		if ($mentionedUser) {
			query("INSERT INTO notifications (type, level, recipient, sender) VALUES (?,?,?,?)",
				[$nType + 10, $id, $mentionedUser, $userdata['id']]);
		}
	}
} else {
	if (!$cmntId) die('params pls');
	if ($userdata['powerlevel'] < 2) die('perms pls');

	// "deleting" a comment toggles the deleted value. This allows us to reuse the exact same code for undeleting comments!

	$temp = result("SELECT deleted FROM comments WHERE id = ?", [$cmntId]);
	$deleted = ($temp ? 0 : 1);

	query("UPDATE comments SET deleted = ? WHERE id = ?", [$deleted, $cmntId]);
}

redirect(sprintf('/%s.php?id=%s%s', $type, $id, ($type == 'user' ? '&forceuser' : '')));