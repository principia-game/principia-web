<?php
require('lib/common.php');

$action = $_POST['action'] ?? null;

needsLogin();

$topbot = [
	'breadcrumb' => [['href' => "private", 'title' => 'Private messages']],
	'title' => 'Send'
];

if ($userdata['powerlevel'] < 1) error('403', 'You have no permissions to do this!');

$userto = $_POST['userto'] ?? '';
$title = $_POST['title'] ?? '';
$message = $_POST['message'] ?? '';

$error = '';

// Submitting a PM
if ($action == 'Submit') {
	$userto = result("SELECT id FROM users WHERE name LIKE ?", [$userto]);

	if ($userto && $message) {
		$recentpms = fetch("SELECT date FROM z_pmsgs WHERE date >= (UNIX_TIMESTAMP() - 30) AND userfrom = ?", [$userdata['id']]);
		if ($recentpms) {
			$error = "You can't send more than one PM within 30 seconds!";
		} else {
			query("INSERT INTO z_pmsgs (date,userto,userfrom,title,text) VALUES (?,?,?,?,?)",
				[time(),$userto,$userdata['id'],$title,$message]);
			$nextId = insertId();

			query("INSERT INTO notifications (type, level, recipient, sender) VALUES (?,?,?,?)",
				[3, $nextId, $userto, $userdata['id']]);

			redirect("private");
		}
	} elseif (!$userto) {
		$error = "That user doesn't exist!";
	} elseif (!$message) {
		$error = "You can't send a blank message!";
	}
}

// Default
if (!$action) {
	if (isset($_GET['pid']) && $pid = $_GET['pid']) {
		$post = fetch("SELECT u.name name, p.title, p.text "
			."FROM z_pmsgs p LEFT JOIN users u ON p.userfrom = u.id "
			."WHERE p.id = ?" . ($userdata['powerlevel'] < 4 ? " AND (p.userfrom=".$userdata['id']." OR p.userto=".$userdata['id'].")" : ''), [$pid]);
		if ($post) {
			$quotetext = sprintf(
				'[reply="%s" id="%s"]%s[/reply]'.PHP_EOL.PHP_EOL,
			$post['name'], $pid, $post['text']);

			$title = 'Re: ' . $post['title'];
			$userto = $post['name'];
		}
	}

	if (isset($_GET['uid']) && $uid = $_GET['uid']) {
		$userto = result("SELECT name FROM users WHERE id = ?", [$uid]);
	} elseif (!isset($userto)) {
		$userto = $_POST['userto'];
	}
} else if ($action == 'Preview') { // Previewing PM
	$post['date'] = $post['ulastpost'] = time();
	$post['text'] = $_POST['message'];
	foreach ($userdata as $field => $val)
		$post['u'.$field] = $val;
	$post['headerbar'] = $title.' (Message preview)';

	$topbot['title'] .= ' (Preview)';
}

echo _twigloader()->render('sendprivate.twig', [
	'post' => $post ?? null,
	'userto' => $userto,
	'messagetitle' => $title,
	'message' => $message,
	'topbot' => $topbot,
	'action' => $action,
	'error' => $error
]);
