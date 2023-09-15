<?php
$arg = $path[2] ?? null;

if (isset($_GET['id'])) redirect('/user/'.$_GET['id']);

if (is_numeric($arg))
	$user = fetch("SELECT * FROM users WHERE id = ?", [$arg]);
else
	$user = fetch("SELECT * FROM users WHERE name = ?", [$arg]);

if (!isset($user) || !$user) error('404');

$id = $user['id'];

$page = $_GET['page'] ?? 1;
$forceuser = isset($_GET['forceuser']);

if (isset($_GET['markread']))
	$action = 'markread';
elseif (isset($_GET['edited']))
	$action = 'edited';
elseif (isset($_GET['justbanned']))
	$action = $_GET['justbanned'];

// Personal user page stuff
if (isset($userdata['id']) && $userdata['id'] == $id && !$forceuser) {
	if (isset($_GET['markread'])) {
		query("DELETE FROM notifications WHERE recipient = ?", [$userdata['id']]);
		$notificationCount = 0;
	}

	$notifsdata = fetchArray(query("SELECT n.*, l.id l_id, l.title l_title, $userfields
			FROM notifications n LEFT JOIN levels l ON n.level = l.id JOIN users u ON n.sender = u.id
			WHERE n.recipient = ?",
		[$userdata['id']]));

	$notifications = prepareNotifications($notifsdata, $userdata['id']);

	twigloader()->display('user.twig', [
		'id' => $id,
		'name' => $userdata['name'],
		'forceuser' => $forceuser,
		'notifs' => $notifications ?? [],
		'action' => $action ?? null
	]);
} else { // general profile details stuff

	$levels = query("SELECT l.id id,l.title title, $userfields
			FROM levels l JOIN users u ON l.author = u.id
			WHERE l.author = ? AND l.visibility = 0 ORDER BY l.id DESC ".paginate($page, LPP),
		[$id]);

	$count = $cache->hit('levelcount_'.$id, function () use ($id) {
		return result("SELECT COUNT(*) FROM levels l WHERE l.author = ? AND l.visibility = 0", [$id]);
	});

	$wikiconts = result("SELECT COUNT(*) FROM wikirevisions WHERE author = ?", [$id]);

	$comments = query("SELECT $userfields, c.* FROM comments c JOIN users u ON c.author = u.id WHERE c.type = 4 AND c.level = ? ORDER BY c.time DESC", [$id]);

	if (isset($userdata['id']) && $id == $userdata['id'])
		query("DELETE FROM notifications WHERE type = 2 AND recipient = ?", [$userdata['id']]);

	clearMentions('user', $id);

	twigloader()->display('user.twig', [
		'id' => $id,
		'name' => $user['name'],
		'userpagedata' => $user,
		'levels' => fetchArray($levels),
		'forceuser' => $forceuser,
		'page' => $page,
		'level_count' => $count,
		'action' => $action ?? null,
		'comments' => $comments ?? null,
		'wikiconts' => $wikiconts
	]);
}
