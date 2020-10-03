<?php
require('lib/common.php');

$lid = (isset($_GET['id']) ? $_GET['id'] : 0);

$level = fetch("SELECT l.*, u.id u_id, u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

if ($log) {
	$hasLiked = result("SELECT COUNT(*) FROM likes WHERE user = ? AND level = ?", [$userdata['id'], $lid]) == 1 ? true : false;
	if (isset($_GET['vote'])) {
		if (!$hasLiked) {
			query("UPDATE levels SET likes = likes + '1' WHERE id = ?", [$lid]);
			query("INSERT INTO likes VALUES (?,?)", [$userdata['id'], $lid]);
		}
		die();
	}
}

if (!isset($hasLiked)) $hasLiked = false;

query("UPDATE levels SET views = views + '1' WHERE id = ?", [$lid]);
$level['views']++;

$bbCode = new \Genert\BBCode\BBCode();
$bbCode->addLinebreakParser();
$level['description'] = $bbCode->convertToHtml($level['description']);

$comments = query("SELECT c.*,u.id u_id,u.name u_name FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 1 AND c.level = ? ORDER BY c.time DESC", [$lid]);

// TODO: Increment downloads.
$twig = twigloader();

echo $twig->render('level.php', [
	'lid' => $lid,
	'level' => $level,
	'has_liked' => $hasLiked,
	'bbCode' => $bbCode,
	'comments' => fetchArray($comments)
]);
