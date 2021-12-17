<?php
require('lib/common.php');

if ($userdata['powerlevel'] < 2) error('403', "You shouldn't be here, get out!");

$id = (isset($_GET['id']) ? $_GET['id'] : null);
$action = (isset($_POST['action']) ? $_POST['action'] : null);

$banuserdata = fetch("SELECT id, name, customcolor, powerlevel FROM users WHERE id = ?", [$_GET['id']]);

if ($action == "Ban User") {
	$reason = (isset($_POST['reason']) && !empty($_POST['reason']) ? $_POST['reason'] : '(No reason specified)');

	query("INSERT INTO bans (user, banner, reason, time) VALUES (?,?,?,?)", [$id, $userdata['id'], $reason, time()]);
	query("UPDATE users SET powerlevel = 0 WHERE id = ?", [$id]);

	redirect(sprintf("/user.php?id=%s&justbanned=ban", $id));
} elseif ($action == "Unban User") {
	query("UPDATE bans SET old = 1 WHERE user = ? AND old = NULL", [$id]);
	query("UPDATE users SET powerlevel = 1 WHERE id = ?", [$id]);

	redirect(sprintf("/user.php?id=%s&justbanned=unban", $id));
}

$twig = twigloader();
echo $twig->render('admin_ban_user.twig', [
	'banuserdata' => $banuserdata,
	'unban' => $banuserdata['powerlevel'] < 1
]);
