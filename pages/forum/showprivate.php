<?php
needsLogin();

$fieldlist = userfields('u', 'u').','.userfields_post().',';

$pid = $_GET['id'] ?? null;

$pmsg = fetch("SELECT $fieldlist p.* FROM z_pmsgs p LEFT JOIN users u ON u.id = p.userfrom WHERE p.id = ?", [$pid]);
if (!$pmsg) error('404');
$tologuser = ($pmsg['userto'] == $userdata['id']);

if ((!$tologuser && $pmsg['userfrom'] != $userdata['id']) && !IS_ROOT)
	error('404');
elseif ($tologuser && $pmsg['unread']) {
	query("UPDATE z_pmsgs SET unread = 0 WHERE id = ?", [$pid]);
	query("DELETE FROM notifications WHERE type = 3 AND level = ? AND recipient = ?", [$pid, $userdata['id']]);
}

$pagebar = [
	'breadcrumb' => ["private" => 'Private messages'],
	'title' => $pmsg['title'] ?: '(untitled)',
	'actions' => ["sendprivate?pid=$pid" => 'Reply']
];

$pmsg['id'] = 0;

twigloaderForum()->display('forum/showprivate.twig', [
	'pagebar' => $pagebar,
	'pmsg' => $pmsg
]);
