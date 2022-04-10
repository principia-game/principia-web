<?php
require('lib/common.php');

$id = $_GET['id'] ?? null;

if (isset($_GET['grf']) && $userdata['powerlevel'] > 2) {
	$generateResetFor = $_GET['grf'] ?? null;

	if ($generateResetFor) {
		$tok = bin2hex(random_bytes(32));

		query("INSERT INTO passwordresets (id, user, time) VALUES (?,?,?)",
			[$tok, $generateResetFor, time()]);

		printf("/resetpassword.php?id=%s", $tok);
	}
	die();
}

$resetdata = fetch("SELECT pr.*, u.name FROM passwordresets pr JOIN users u ON pr.user = u.id WHERE pr.id = ?", [$id]);

if (!$resetdata) error('403', "nononononono");
if ((time() - $resetdata['time']) >= 60*15) error('403', "Password reset request expired.");
if (!$resetdata['active']) error('403', "Your password has already been reset by this request.");

$error = '';

if (isset($_POST['action'])) {
	$pass = $_POST['pass'] ?? null;
	$pass2 = $_POST['pass2'] ?? null;

	if ($pass != $pass2) $error .= "Passwords aren't identical.";

	if ($error == '') {
		query("UPDATE users SET password = ? WHERE id = ?", [password_hash($pass, PASSWORD_DEFAULT), $resetdata['user']]);
		query("UPDATE passwordresets SET active = 0 WHERE id = ?", [$id]);

		redirect('/login.php?resetted');
	}
}

$twig = twigloader();
echo $twig->render('resetpassword.twig', [
	'resetdata' => $resetdata
]);