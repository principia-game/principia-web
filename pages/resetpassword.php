<?php

$id = $_GET['id'] ?? null;

if (isset($_GET['grf']) && IS_ADMIN) {
	$generateResetFor = $_GET['grf'] ?? null;

	if ($generateResetFor) {
		$tok = generatePasswordReset($generateResetFor);

		printf("/resetpassword?id=%s", $tok);
	}
	die();
}

$resetdata = fetch("SELECT pr.*, u.name FROM passwordresets pr JOIN users u ON pr.user = u.id WHERE pr.id = ?", [$id]);

if (!$resetdata)
	error('403');

if ((time() - $resetdata['time']) >= 60*60*24)
	error('403', "Password reset request expired (requests are only valid for 24 hours).");

if (!$resetdata['active'])
	error('403', "Your password has already been reset by this request.");

$error = '';

if (isset($_POST['action'])) {
	$pass = $_POST['pass'] ?? null;
	$pass2 = $_POST['pass2'] ?? null;

	if ($pass != $pass2) $error .= "Passwords aren't identical.";

	if ($error == '') {
		query("UPDATE users SET password = ? WHERE id = ?", [password_hash($pass, PASSWORD_DEFAULT), $resetdata['user']]);
		query("UPDATE passwordresets SET active = 0 WHERE id = ?", [$id]);

		redirect($log ? '/settings' : '/login?resetted');
	}
}

twigloader()->display('resetpassword.twig', [
	'resetdata' => $resetdata
]);
