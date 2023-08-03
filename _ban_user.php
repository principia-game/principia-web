<?php
require('lib/common.php');

if ($userdata['rank'] < 2) error('403', "You shouldn't be here, get out!");

$id = $_GET['id'] ?? null;
$action = $_POST['action'] ?? null;

$banuserdata = fetch("SELECT id, name, customcolor, rank FROM users WHERE id = ?", [$_GET['id']]);

if ($action == "Ban User") {
	$reason = (isset($_POST['reason']) && !empty($_POST['reason']) ? $_POST['reason'] : '(No reason specified)');

	query("INSERT INTO bans (user, banner, reason, time) VALUES (?,?,?,?)", [$id, $userdata['id'], $reason, time()]);
	query("UPDATE users SET rank = -1 WHERE id = ?", [$id]);

	redirect(sprintf("/user/%s?justbanned=ban", $id));
} elseif ($action == "Unban User") {
	query("UPDATE bans SET old = 1 WHERE user = ? AND old = 0", [$id]);
	query("UPDATE users SET rank = 1 WHERE id = ?", [$id]);

	redirect(sprintf("/user/%s?justbanned=unban", $id));
}

echo twigloader()->render('admin_ban_user.twig', [
	'banuserdata' => $banuserdata,
	'unban' => $banuserdata['rank'] < 0
]);
