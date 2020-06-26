<?php
require('lib/common.php');

$id = isset($_POST['id']) ? $_POST['id'] : null;
$type = isset($_POST['t']) ? $_POST['t'] : null;
$message = isset($_POST['comment']) ? $_POST['comment'] : null;

if (!$log) die('login pls');
if (!$type || !$id || !$message) die('params pls');
if (result("SELECT COUNT(*) FROM levels WHERE id = ?", [$id]) != 1) die('valid level pls');

query("INSERT INTO comments (level, author, time, message) VALUES (?,?,?,?)",
	[$id, $userdata['id'], time(), $message]);

header('Location: /level.php?id='.$id);