<?php
$action = $_POST['action'] ?? null;

needsLogin();

$topbot = [
	'breadcrumb' => ["private" => 'Private messages'],
	'title' => 'Send'
];

if (IS_BANNED) error('403', 'You have no permissions to do this!');

$userto = $_POST['userto'] ?? '';
$title = $_POST['title'] ?? '';
$message = $_POST['message'] ?? '';

$error = '';

// Submitting a PM
if ($action == 'Submit') {
	$userto = result("SELECT id FROM users WHERE name LIKE ?", [$userto]);

	if (!$userto) $error = "That user doesn't exist.";
	if (!$message) $error = "You can't send a blank message.";

	$recentpms = fetch("SELECT date FROM z_pmsgs WHERE date >= (UNIX_TIMESTAMP() - 30) AND userfrom = ?", [$userdata['id']]);
	if ($recentpms)
		$error = "You can't send more than one PM within 30 seconds!";

	if (!$error) {
		insertInto('z_pmsgs', [
			'date' => time(),
			'userto' => $userto,
			'userfrom' => $userdata['id'],
			'title' => $title,
			'text' => $message
		]);

		$nextId = insertId();
		insertInto('notifications', [
			'type' => 3,
			'level' => $nextId,
			'recipient' => $userto,
			'sender' => $userdata['id']
		]);

		redirect("private");
	}
}

// Default
if (!$action) {
	if (isset($_GET['pid']) && $pid = $_GET['pid']) {
		$post = fetch("SELECT u.name name, p.title, p.text FROM z_pmsgs p LEFT JOIN users u ON p.userfrom = u.id WHERE p.id = ?"
			.(!IS_ROOT ? " AND (p.userfrom=".$userdata['id']." OR p.userto=".$userdata['id'].")" : ''), [$pid]);

		if ($post) {
			$quotetext = sprintf(
				'[reply="%s" id="%s"]%s[/reply]'.PHP_EOL.PHP_EOL,
			$post['name'], $pid, $post['text']);

			$title = 'Re: ' . $post['title'];
			$userto = $post['name'];
		}
	}

	if (isset($_GET['uid']) && $uid = $_GET['uid'])
		$userto = result("SELECT name FROM users WHERE id = ?", [$uid]);
	elseif (!isset($userto))
		$userto = $_POST['userto'];

} elseif ($action == 'Preview') { // Previewing PM
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;

	$post['date'] = $post['ulastpost'] = time();
	$post['text'] = $_POST['message'];
	$post['headerbar'] = $title.' (Message preview)';

	$topbot['title'] .= ' (Preview)';
}

echo twigloaderForum()->render('sendprivate.twig', [
	'post' => $post ?? null,
	'userto' => $userto,
	'messagetitle' => $title,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'error' => $error
]);
