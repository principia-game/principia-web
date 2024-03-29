<?php

if (!IS_MOD) error('403');

$id = $_GET['id'] ?? null;
$action = $_POST['action'] ?? null;

$banuserdata = fetch("SELECT id, name, customcolor, rank FROM users WHERE id = ?", [$_GET['id']]);

if ($action == "Ban User") {
	$reason = (isset($_POST['reason']) && !empty($_POST['reason']) ? $_POST['reason'] : '(No reason specified)');

	insertInto('bans', [
		'user' => $id,
		'banner' => $userdata['id'],
		'reason' => $reason,
		'time' => time()
	]);

	query("UPDATE users SET rank = -1 WHERE id = ?", [$id]);

	redirect("/user/%s?justbanned=ban", $id);
} elseif ($action == "Unban User") {
	query("UPDATE bans SET old = 1 WHERE user = ? AND old = 0", [$id]);
	query("UPDATE users SET rank = 1 WHERE id = ?", [$id]);

	redirect("/user/%s?justbanned=unban", $id);
}

twigloader()->display('admin/ban_user.twig', [
	'banuserdata' => $banuserdata,
	'unban' => $banuserdata['rank'] < 0
]);
