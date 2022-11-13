<?php
require('lib/common.php');
needsLogin();

$fieldlist = userfields('u', 'u').','.userfields_post();

$pid = $_GET['id'] ?? null;

$pmsgs = fetch("SELECT $fieldlist p.* FROM z_pmsgs p LEFT JOIN users u ON u.id = p.userfrom WHERE p.id = ?", [$pid]);
if ($pmsgs == null) error("404", "Private message does not exist.");
$tologuser = ($pmsgs['userto'] == $userdata['id']);

if ((!$tologuser && $pmsgs['userfrom'] != $userdata['id']) && $userdata['powerlevel'] < 4)
	error("404", "Private message does not exist.");
elseif ($tologuser && $pmsgs['unread']) {
	query("UPDATE z_pmsgs SET unread = 0 WHERE id = ?", [$pid]);
	query("DELETE FROM notifications WHERE type = 3 AND level = ? AND recipient = ?", [$pid, $userdata['id']]);
}

$pagebar = [
	'breadcrumb' => ["private" => 'Private messages'],
	'title' => $pmsgs['title'] ?: '(untitled)',
	'actions' => ["sendprivate?pid=$pid" => 'Reply']
];

$pmsgs['id'] = 0;

echo _twigloader()->render('showprivate.twig', [
	'pagebar' => $pagebar,
	'pmsgs' => $pmsgs
]);
