<?php
require('lib/common.php');

needsLogin();

$action = $_POST['action'] ?? null;
$fid = $_GET['id'] ?? null;

$forum = fetch("SELECT * FROM z_forums WHERE id = ? AND ? >= minread", [$fid, $userdata['powerlevel']]);

if (!$forum)
	error("404", "Forum does not exist.");
if ($forum['minthread'] > $userdata['powerlevel'])
	error("403", "You have no permissions to create threads in this forum!");

$error = '';

$title = $_POST['title'] ?? '';
$message = $_POST['message'] ?? '';

if ($action == 'Submit') {
	if (strlen(trim($title)) < 15)
		$error = "You need to enter a longer title.";
	if (strlen(trim($message)) == 0)
		$error = "You need to enter a message to your thread.";
	if ($userdata['lastpost'] > time() - 30 && $action == 'Submit' && $userdata['powerlevel'] < 4) // && !hasPerm('ignore-thread-time-limit')
		$error = "Don't post threads so fast, wait a little longer.";
	//if ($userdata['lastpost'] > time() - 2 && $action == 'Submit' && hasPerm('ignore-thread-time-limit'))
	//	$error = "You must wait 2 seconds before posting a thread.";

	if (!$error) {
		query("UPDATE users SET posts = posts + 1, threads = threads + 1, lastpost = ? WHERE id = ?",
			[time(), $userdata['id']]);

		query("INSERT INTO z_threads (title, forum, user, lastdate, lastuser) VALUES (?,?,?,?,?)",
			[$title, $fid, $userdata['id'], time(), $userdata['id']]);

		$tid = insertId();
		query("INSERT INTO z_posts (user, thread, date) VALUES (?,?,?)",
			[$userdata['id'], $tid, time()]);

		$pid = insertId();
		query("INSERT INTO z_poststext (id, text) VALUES (?,?)", [$pid, $message]);

		query("UPDATE z_forums SET threads = threads + 1, posts = posts + 1, lastdate = ?,lastuser = ?,lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $fid]);

		query("UPDATE z_threads SET lastid = ? WHERE id = ?", [$pid, $tid]);

		newForumPostHook([
			'id' => $pid,
			'title' => $title,
			'content' => $message,
			'u_id' => $userdata['id'],
			'u_name' => $userdata['name']
		], 'thread');

		redirect("thread?id=$tid");
	}
}

$topbot = [
	'breadcrumb' => [['href' => "forum?id=$fid", 'title' => $forum['title']]],
	'title' => "New thread"
];

if ($action == 'Preview') {
	$post['date'] = $post['ulastpost'] = time();
	$post['text'] = $message;
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;
	$post['headerbar'] = 'Post preview';

	$topbot['title'] .= ' (Preview)';
}

echo _twigloader()->render('newthread.twig', [
	'post' => $post ?? null,
	'threadtitle' => $title,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'fid' => $fid,
	'error' => $error
]);