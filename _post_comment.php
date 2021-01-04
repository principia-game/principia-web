<?php
require('lib/common.php');

$id = isset($_POST['id']) ? $_POST['id'] : null;
$type = isset($_POST['t']) ? $_POST['t'] : null;
$message = isset($_POST['comment']) ? $_POST['comment'] : null;

if (!$log) die('login pls');
if (!$type || !$id || !$message) die('params pls');
if (result("SELECT COUNT(*) FROM levels WHERE id = ?", [$id]) != 1) die('valid level pls');

if (!$nType = cmtTypeToNum($type)) {
	die('valid type pls');
}

query("INSERT INTO comments (type, level, author, time, message) VALUES (?,?,?,?,?)",
	[$nType, $id, $userdata['id'], time(), $message]);

if ($type == 'level') {
	$leveldata = fetch("SELECT id, author FROM levels WHERE id = ?", [$id]);

	query("INSERT INTO notifications (type, level, recipient, sender) VALUES (?,?,?,?)",
		[1, $leveldata['id'], $leveldata['author'], $userdata['id']]);
}

redirect(sprintf('/%s.php?id=%s', $type, $id));