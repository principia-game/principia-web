<?php
needsLogin();

if (getUserCountry() == 'GB')
	error(451, "The principia-web PM system is not available for users accessing the website from the United Kingdom, due to the Online Safety Act.");

$page = (int)($_GET['page'] ?? 1);
$view = $_GET['view'] ?? 'read';
$sent = $view == 'sent';

if ($sent) {
	$fieldn = 'to';
	$fieldn2 = 'from';
} else {
	$fieldn = 'from';
	$fieldn2 = 'to';
}

$showdel = isset($_GET['showdel']);

if (isset($_GET['action']) && $_GET['action'] == "del") {
	$owner = result("SELECT user$fieldn2 FROM z_pmsgs WHERE id = ?", [$userdata['id']]);
	if ($owner == $userdata['id'])
		query("UPDATE z_pmsgs SET del_$fieldn2 = ? WHERE id = ?", [!$showdel, $userdata['id']]);
	else
		error('403', "You are not allowed to (un)delete that message.");
}

$ptitle = 'Private messages' . ($sent ? ' (sent)' : '');

$pmsgc = result("SELECT COUNT(*) FROM z_pmsgs
		WHERE user$fieldn2 = ? AND del_$fieldn2 = ?",
	[$userdata['id'], $showdel]);

$ufields = userfields('u', 'u');
$pmsgs = query("SELECT $ufields, p.* FROM z_pmsgs p
		LEFT JOIN users u ON u.id = p.user$fieldn
		WHERE p.user$fieldn2 = ? AND del_$fieldn2 = ?
		ORDER BY p.unread DESC, p.date DESC
		".paginate($page, TPP),
	[$userdata['id'], $showdel]);

$topbot = ['title' => $ptitle];

if ($sent)
	$topbot['actions'] = ['private' => "View received"];
else
	$topbot['actions'] = ['private?view=sent' => "View sent"];

$topbot['actions']['sendprivate'] = 'Send new';

twigloaderForum()->display('forum/private.twig', [
	'pmsgs' => $pmsgs,
	'pmsgc' => $pmsgc,
	'topbot' => $topbot,
	'fieldn' => $fieldn,
	'view' => $view,
	'sent' => $sent,
	'page' => $page,
]);
