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
} else {
	if (!$cmntId) die('params pls');
	if ($userdata['powerlevel'] < 2) die('perms pls');

	// "deleting" a comment toggles the deleted value. This allows us to reuse the exact same code for undeleting comments!

	$temp = result("SELECT deleted FROM comments WHERE id = ?", [$cmntId]);
	$deleted = ($temp ? 0 : 1);

	query("UPDATE comments SET deleted = ? WHERE id = ?", [$deleted, $cmntId]);
}

redirect(sprintf('/%s.php?id=%s%s', $type, $id, ($type == 'user' ? '&forceuser' : '')));