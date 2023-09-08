<?php
$id = $_POST['id'] ?? null;
$type = $_POST['t'] ?? null;
$message = $_POST['comment'] ?? null;
$doDelete = isset($_POST['del']);
$cmntId = $_POST['cmnt'] ?? false;

if (!$log) die('login pls');
if (!$type || !$id) die('params pls');
if (IS_BANNED) die('you have been banned');

if ($type != 'chat')
	$url = sprintf('/%s/%s%s#comments', $type, $id, ($type == 'user' ? '?forceuser' : ''));
else
	$url = "/chat";

if (!$doDelete) {
	if (!$message) die('params pls');
	//if (result("SELECT COUNT(*) FROM levels WHERE id = ?", [$id]) != 1) die('valid level pls');

	// prevent double posting
	$doublePostCheck = result("SELECT COUNT(*) FROM comments WHERE author = ? AND time > ?", [$userdata['id'], time() - 5]);
	if ($doublePostCheck) // do nothing, just redirect like normal
		redirect($url);

	// rate-limit one user to only being able to comment 5 times every 10 minutes. it should be fair enough to not prevent legitimate use
	$recentCommentCountUser = result("SELECT COUNT(*) FROM comments WHERE author = ? AND time > ?", [$userdata['id'], time() - (10 * 60)]);
	if ($recentCommentCountUser > 5)
		die('Please wait a while until commenting again.');

	// also rate-limit globally to only allow 30 comments every 15 minutes, should be high enough to not hinder actual comments
	//   (principia-web gets, on an extremely liberal overcalculation over its entire existence, 1 comment per hour)
	$recentCommentCount = result("SELECT COUNT(*) FROM comments WHERE time > ?", [time() - (15 * 60)]);
	if ($recentCommentCount > 30)
		die('Please wait a while until commenting again.');

	if (!$nType = cmtTypeToNum($type))
		die('valid type pls');

	insertInto('comments', [
		'type' => $nType,
		'level' => $id,
		'author' => $userdata['id'],
		'time' => time(),
		'message' => $message
	]);

	switch ($type) {
		case 'level':
			$leveldata = fetch("SELECT id, author FROM levels WHERE id = ?", [$id]);

			if ($userdata['id'] == $leveldata['author']) break;

			insertInto('notifications', [
				'type' => 1,
				'level' => $leveldata['id'],
				'recipient' => $leveldata['author'],
				'sender' => $userdata['id']
			]);
		break;
		case 'user':
			if ($userdata['id'] == $id) break;

			insertInto('notifications',
				['type' => 2, 'recipient' => $id, 'sender' => $userdata['id']]);
		break;
	}

	preg_match('/@([A-Za-z0-9_-]+)/', $message, $matches);
	if (isset($matches[1])) {
		$mentionedUser = result("SELECT id FROM users WHERE name = ?", [$matches[1]]);
		if ($mentionedUser) {
			insertInto('notifications', [
				'type' => $nType + 10,
				'level' => $id,
				'recipient' => $mentionedUser,
				'sender' => $userdata['id']
			]);
		}
	}
} else {
	if (!$cmntId) die('params pls');
	if (!IS_MOD) die('perms pls');

	// "deleting" a comment toggles the deleted value. This allows us to reuse the exact same code for undeleting comments!

	$temp = result("SELECT deleted FROM comments WHERE id = ?", [$cmntId]);
	$deleted = ($temp ? 0 : 1);

	query("UPDATE comments SET deleted = ? WHERE id = ?", [$deleted, $cmntId]);
}

// Invalidate index comments, even if a comment is deleted it might show up.
$cachectrl->invIndexComments();

redirect($url."?commented");
