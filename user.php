<?php
include('lib/common.php');

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
} else {
	error('404', "No user specified.");
}

$page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? $_GET['page'] : 1);
$forceuser = isset($_GET['forceuser']);

$limit = sprintf("LIMIT %s,%s", (($page - 1) * $lpp), $lpp);
$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.author = ? ORDER BY l.id DESC $limit",
	[$userpagedata['id']]);
$count = result("SELECT COUNT(*) FROM levels l WHERE l.author = ?", [$userpagedata['id']]);

// Personal user page stuff
if ($userdata['id'] == $userpagedata['id']) {
	if ($log && isset($_GET['darkmode'])) {
		$newopt = ($userdata['darkmode'] ? 0 : 1);

		query("UPDATE users SET darkmode = ? WHERE id = ?", [$newopt, $userdata['id']]);
		$userdata['darkmode'] = $newopt;
	}

	$notifsdata = query("SELECT n.*, l.id l_id, l.title l_title, u.id u_id, u.name u_name FROM notifications n JOIN levels l ON n.level = l.id JOIN users u ON n.sender = u.id WHERE n.recipient = ?", [$userpagedata['id']]);

	$notifications = [];
	while ($notifdata = $notifsdata->fetch()) {
		$notifications[] = sprintf('%s commented on your level <a href="level.php?id=%s">%s</a>.', userlink($notifdata, 'u_'), $notifdata['l_id'], $notifdata['l_title']);
	}
}

$twig = twigloader();
echo $twig->render('user.twig', [
	'id' => $userpagedata['id'],
	'name' => $userpagedata['name'],
	'levels' => fetchArray($levels),
	'forceuser' => $forceuser,
	'page' => $page,
	'level_count' => $count,
	'notifs' => (isset($notifications) ? $notifications : [])
]);