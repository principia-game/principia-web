<?php
needsLogin();

$action = $_POST['action'] ?? null;
$tid = $_GET['id'] ?? null;

$thread = fetch("SELECT t.*, f.title ftitle, f.minreply fminreply
	FROM z_threads t LEFT JOIN z_forums f ON f.id=t.forum
	WHERE t.id = ? AND ? >= f.minread", [$tid, $userdata['rank']]);

if (!$thread)
	error('404');
if ($thread['fminreply'] > $userdata['rank'])
	error('403', "You have no permissions to create posts in this forum!");
if ($thread['closed'] && !IS_MOD)
	error('400', "You can't post in closed threads.");

$error = '';

$message = $_POST['message'] ?? '';

if ($action == 'Submit') {
	$lastpost = fetch("SELECT id,user,date FROM z_posts WHERE thread = ? ORDER BY id DESC LIMIT 1", [$thread['id']]);
	if ($lastpost['user'] == $userdata['id'] && $lastpost['date'] >= (time() - 86400) && !IS_ADMIN)
		$error = "You can't double post until it's been at least one day!";
	if ($lastpost['user'] == $userdata['id'] && $lastpost['date'] >= (time() - 2) && IS_ADMIN)
		$error = "You must wait 2 seconds before posting consecutively.";
	if (strlen(trim($message)) == 0)
		$error = "Your post is empty! Enter a message and try again.";
	if (strlen(trim($message)) < 25)
		$error = "Your post is too short to be meaningful. Please try to write something longer.";

	if (!$error) {
		query("UPDATE users SET posts = posts + 1, lastpost = ? WHERE id = ?",
			[time(), $userdata['id']]);

		insertInto('z_posts', ['user' => $userdata['id'], 'thread' => $tid, 'date' => time()]);

		$pid = insertId();
		insertInto('z_poststext', ['id' => $pid, 'text' => $message]);

		query("UPDATE z_threads SET posts = posts + 1,lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $tid]);

		query("UPDATE z_forums SET posts = posts + 1,lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
			[time(), $userdata['id'], $pid, $thread['forum']]);

		// nuke entries of this thread in the "threadsread" table
		query("DELETE FROM z_threadsread WHERE tid = ? AND NOT (uid = ?)", [$thread['id'], $userdata['id']]);

		newForumPostHook([
			'id' => $pid,
			'title' => $thread['title'],
			'content' => $message,
			'u_id' => $userdata['id'],
			'u_name' => $userdata['name']
		], 'reply');

		redirect("thread?pid=$pid#$pid");
	}
}

$topbot = [
	'breadcrumb' => [
		"forum?id={$thread['forum']}" => $thread['ftitle'],
		"thread?id={$thread['id']}" => $thread['title']],
	'title' => "New reply"
];

$pid = $_GET['pid'] ?? 0;
if ($pid) {
	$post = fetch("SELECT u.name name, p.user, pt.text, f.id fid, p.thread, f.minread
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			LEFT JOIN z_threads t ON t.id = p.thread
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE p.id = ?", [$pid]);

	//does the user have reading access to the quoted post?
	if ($userdata['rank'] < $post['minread']) {
		$post['name'] = 'ROllerozxa';
		$post['text'] = 'uwu';
	}

	$message = sprintf(
		'[quote="%s" id="%s"]%s[/quote]'.PHP_EOL.PHP_EOL,
	$post['name'], $pid, $post['text']);
}

if ($action == 'Preview') {
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;

	$post['date'] = $post['ulastpost'] = time();
	$post['text'] = $message;
	$post['headerbar'] = 'Post preview';

	$topbot['title'] .= ' (Preview)';
}

$fieldlist = userfields('u', 'u') . ', u.posts uposts, ';
$newestposts = query("SELECT $fieldlist p.*, pt.text
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			WHERE p.thread = ? AND p.deleted = 0
			ORDER BY p.id DESC LIMIT 5", [$tid]);

echo twigloaderForum()->render('newreply.twig', [
	'post' => $post ?? null,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'tid' => $tid,
	'error' => $error,
	'newestposts' => $newestposts
]);
