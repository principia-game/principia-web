<?php
require('lib/common.php');

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
}

if (!isset($userpagedata) || !$userpagedata) error('404', "No user specified.");

$id = $userpagedata['id'];

$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
$forceuser = isset($_GET['forceuser']);

if (isset($_GET['markread']))
	$action = 'markread';
elseif (isset($_GET['edited']))
	$action = 'edited';
elseif (isset($_GET['justbanned']))
	$action = $_GET['justbanned'];

$twig = twigloader();

// Personal user page stuff
if (isset($userdata['id']) && $userdata['id'] == $id && !$forceuser) {
	if (isset($_GET['markread'])) {
		query("DELETE FROM notifications WHERE recipient = ?", [$userdata['id']]);
		$notificationCount = 0;
	}

	$notifsdata = query("SELECT $userfields n.*, l.id l_id, l.title l_title
			FROM notifications n LEFT JOIN levels l ON n.level = l.id JOIN users u ON n.sender = u.id
			WHERE n.recipient = ?",
		[$userdata['id']]);

	$notifications = [];
	while ($notifdata = $notifsdata->fetch()) {
		switch ($notifdata['type']) {
			case 1:
				$notifications[] = sprintf(
					'%s commented on your level <a href="/level.php?id=%s">%s</a>.',
				userlink($notifdata, 'u_'), $notifdata['l_id'], $notifdata['l_title']);
			break;
			case 2:
				$notifications[] = sprintf(
					'%s commented on your <a href="/user.php?id=%s&forceuser">user page</a>.',
				userlink($notifdata, 'u_'), $userdata['id']);
			break;
			case 3:
				$notifications[] = sprintf(
					'%s sent you a private message: <a href="/forum/showprivate.php?id=%s">Read</a>',
				userlink($notifdata, 'u_'), $notifdata['level']);
			break;
			case 11:
			case 12:
			case 13:
			case 14:
			case 15:
			case 16:
				$notifications[] = sprintf(
					'%s mentioned you in a %s comment: <a href="/%s.php?id=%s">Read</a>',
				userlink($notifdata, 'u_'), cmtNumToType($notifdata['type'] - 10), cmtNumToType($notifdata['type'] - 10), $notifdata['level']);
			break;
		}
	}

	echo $twig->render('user.twig', [
		'id' => $id,
		'name' => $userdata['name'],
		'forceuser' => $forceuser,
		'notifs' => $notifications ?? [],
		'action' => $action ?? null
	]);
} else { // general profile details stuff

	$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);

	$levels = query("SELECT $userfields l.id id,l.title title
			FROM levels l JOIN users u ON l.author = u.id
			WHERE l.author = ? AND l.visibility = 0 ORDER BY l.id DESC $limit",
		[$id]);

	$count = $cache->hit('levelcount_'.$id, function () use ($id) {
		return result("SELECT COUNT(*) FROM levels l WHERE l.author = ? AND l.visibility = 0", [$id]);
	});

	$comments = query("SELECT $userfields c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 4 AND c.level = ? ORDER BY c.time DESC", [$id]);

	if (isset($userdata['id']) && $id == $userdata['id']) {
		query("DELETE FROM notifications WHERE type = 2 AND recipient = ?", [$userdata['id']]);
	}

	clearMentions('user', $id);

	echo $twig->render('user.twig', [
		'id' => $id,
		'name' => $userpagedata['name'],
		'userpagedata' => $userpagedata,
		'levels' => fetchArray($levels),
		'forceuser' => $forceuser,
		'page' => $page,
		'level_count' => $count,
		'action' => $action ?? null,
		'comments' => $comments ?? null
	]);
}
