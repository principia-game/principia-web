<?php
needsLogin();

$action = $_POST['action'] ?? null;
$fid = $_GET['id'] ?? null;

$forum = fetch("SELECT * FROM z_forums WHERE id = ? AND ? >= minread", [$fid, $userdata['rank']]);

if (!$forum)
	error('404');
if ($forum['minthread'] > $userdata['rank'])
	error('403', "You have no permissions to create threads in this forum!");

$error = '';

$title = $_POST['title'] ?? '';
$message = $_POST['message'] ?? '';

if ($action == 'Submit') {
	if (strlen(trim($title)) < 15)
		$error = "You need to enter a longer title.";
	if (strlen(trim($message)) == 0)
		$error = "You need to enter a message to your thread.";
	if ($userdata['lastpost'] > time() - (10*60) && $action == 'Submit' && !IS_ROOT)
		$error = "Don't post threads so fast, wait a little longer.";

	if (!$error) {
		insertInto('z_threads', [
			'title' => $title,
			'forum' => $fid,
			'user' => $userdata['id'],
			'lastdate' => time(),
			'lastuser' => $userdata['id']
		]);

		$tid = insertId();
		insertInto('z_posts', [
			'user' => $userdata['id'],
			'thread' => $tid,
			'date' => time()
		]);

		$pid = insertId();
		insertInto('z_poststext', ['id' => $pid, 'text' => $message]);

		query("UPDATE z_forums SET threads = threads + 1, posts = posts + 1, lastdate = ?,lastuser = ?,lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $fid]);

		query("UPDATE z_threads SET lastid = ? WHERE id = ?", [$pid, $tid]);

		query("UPDATE users SET posts = posts + 1, threads = threads + 1, lastpost = ? WHERE id = ?",
			[time(), $userdata['id']]);

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
	'breadcrumb' => ["forum?id=$fid" => $forum['title']],
	'title' => "New thread"
];

if ($action == 'Preview') {
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;

	$post['date'] = $post['ulastpost'] = time();
	$post['text'] = $message;
	$post['headerbar'] = 'Post preview';

	$topbot['title'] .= ' (Preview)';
}

twigloaderForum()->display('forum/newthread.twig', [
	'post' => $post ?? null,
	'threadtitle' => $title,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'fid' => $fid,
	'error' => $error
]);
