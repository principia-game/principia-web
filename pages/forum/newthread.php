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
	$lastpost = fetch("SELECT id, user, date FROM z_posts WHERE user = ? ORDER BY id DESC LIMIT 1", [$userdata['id']]);

	if (strlen(trim($title)) < 15)
		$error = "You need to enter a longer title.";
	if (strlen(trim($message)) == 0)
		$error = "You need to enter a message to your thread.";
	if ($lastpost['date'] > time() - (10*60) && $action == 'Submit' && !IS_ROOT)
		$error = "Don't post threads so fast, wait a little longer.";

	if (!$error) {
		$tid = newThread([
			'forum' => $fid,
			'title' => $title,
			'message' => $message,
			'u_id' => $userdata['id'],
			'u_name' => $userdata['name']
		]);

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

	$post['date'] = time();
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
