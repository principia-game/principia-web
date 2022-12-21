<?php
require('lib/common.php');

$act = $_GET['act'] ?? '';
$action = $_POST['action'] ?? '';

$pid = $_GET['pid'] ?? null;

if ($act == 'delete' || $act == 'undelete') {
	if ($loguser['powerlevel'] <= 1)
		error("You do not have the permission to do this.");

	$sql->query("UPDATE z_posts SET deleted = ? WHERE id = ?", [($act == 'delete' ? 1 : 0), $pid]);
	redirect("thread.php?pid=$pid#$pid");
}

needsLogin();

$thread = fetch("SELECT p.user puser, t.*, f.title ftitle
			FROM z_posts p
			LEFT JOIN z_threads t ON t.id = p.thread
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE p.id = ? AND ? >= f.minread",
		[$pid, $userdata['powerlevel']]);

if (!$thread)
	error('404', "Post doesn't exist.");
if ($thread['closed'] && $userdata['powerlevel'] <= 1)
	error("403", "You can't edit a post in closed threads!");
if ($userdata['powerlevel'] < 3 && $userdata['id'] != $thread['puser'])
	error("403", "You do not have permission to edit this post.");

$editpost = fetch("SELECT u.id, p.user, pt.text
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id WHERE p.id = ?",
		[$pid]);

$message = $_POST['message'] ?? $editpost['text'];

if ($action == 'Submit') {
	if ($editpost['text'] == $_POST['message'])
		error("400", "No changes detected.");

	$newrev = result("SELECT revision FROM z_posts WHERE id = ?", [$pid]) + 1;

	query("UPDATE z_posts SET revision = ? WHERE id = ?", [$newrev, $pid]);

	query("INSERT INTO z_poststext (id,text,revision,date) VALUES (?,?,?,?)",
		[$pid, $_POST['message'], $newrev, time()]);

	redirect("thread?pid=$pid#$pid");
}

$topbot = [
	'breadcrumb' => [
		"forum?id={$thread['forum']}" => $thread['ftitle'],
		"thread?id={$thread['id']}" => $thread['title']],
	'title' => 'Edit post'
];

if ($action == 'Preview') {
	$euser = fetch("SELECT * FROM users WHERE id = ?", [$editpost['id']]);
	foreach ($euser as $field => $val)
		$post['u'.$field] = $val;

	$post['text'] = $message;
	$post['date'] = time();
	$post['headerbar'] = 'Post preview';

	$topbot['title'] .= ' (Preview)';
}

echo _twigloader()->render('editpost.twig', [
	'post' => $post ?? null,
	'topbot' => $topbot,
	'action' => $action,
	'pid' => $pid,
	'message' => $message
]);
