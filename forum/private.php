<?php
require('lib/common.php');

needsLogin();

$page = $_GET['page'] ?? null;
if (!$page) $page = 1;
$view = $_GET['view'] ?? 'read';

if ($view == 'sent') {
	$fieldn = 'to';
	$fieldn2 = 'from';
	$sent = true;
} else {
	$fieldn = 'from';
	$fieldn2 = 'to';
	$sent = false;
}

$id = $_GET['id'] ?? null;

$showdel = isset($_GET['showdel']);

if (isset($_GET['action']) && $_GET['action'] == "del") {
	$owner = result("SELECT user$fieldn2 FROM z_pmsgs WHERE id = ?", [$id]);
	if ($userdata['powerlevel'] > 3 || $owner == $userdata['id']) {
		query("UPDATE z_pmsgs SET del_$fieldn2 = ? WHERE id = ?", [!$showdel, $id]);
	} else {
		error("403", "You are not allowed to (un)delete that message.");
	}
	$id = 0;
}

$ptitle = 'Private messages' . ($sent ? ' (sent)' : '');
if ($id && $userdata['powerlevel'] > 3) {
	$user = fetch("SELECT id,name,group_id FROM users WHERE id = ?", [$id]);
	if ($user == null) error("404", "User doesn't exist.");
	$headtitle = $user['name']."'s ".strtolower($ptitle);
	$title = userlink($user)."'s ".strtolower($ptitle);
} else {
	$id = $userdata['id'];
	$headtitle = $ptitle;
	$title = $ptitle;
}

$ufields = userfields('u', 'u');
$pmsgc = result("SELECT COUNT(*) FROM z_pmsgs WHERE user$fieldn2 = ? AND del_$fieldn2 = ?", [$id, $showdel]);
$pmsgs = query("SELECT $ufields, p.* FROM z_pmsgs p
				LEFT JOIN users u ON u.id = p.user$fieldn
				WHERE p.user$fieldn2 = ?
				AND del_$fieldn2 = ?
				ORDER BY p.unread DESC, p.date DESC
				LIMIT ?,?",
			[$id, $showdel, (($page - 1) * $tpp), $tpp]);

$topbot = [
	'title' => $title
];

if ($sent)
	$topbot['actions'] = [['href' => 'private'.($id != $userdata['id'] ? "?id=$id&" : ''), 'title' => "View received"]];
else
	$topbot['actions'] = [['href' => 'private?'.($id != $userdata['id'] ? "id=$id&" : '').'view=sent', 'title' => "View sent"]];

$topbot['actions'][] = ['href' => 'sendprivate', 'title' => 'Send new'];


$fpagelist = '<br>';
if ($pmsgc > $tpp) {
	if ($id != $userdata['id'])	$furl = "private?id=$id&view=$view";
	else	$furl = "private?view=$view";
	$fpagelist = pagelist($pmsgc, $tpp, $furl, $page).'<br>';
}

echo _twigloader()->render('private.twig', [
	'id' => $id,
	'pmsgs' => $pmsgs,
	'topbot' => $topbot,
	'fieldn' => $fieldn,
	'fpagelist' => $fpagelist,
	'headtitle' => $headtitle
]);
