<?php
include('lib/common.php');

if (isset($_POST['logout'])) {
	setcookie('user', '');
	setcookie('passenc', '');
	redirect('./');
}

if ($log) redirect('./');

$error = '';

if (isset($_GET['resetted'])) $error .= 'Password successfully reset! Please login with your new password.';

if (isset($_POST['action'])) {
	$name = (isset($_POST['name']) ? $_POST['name'] : null);
	$pass = (isset($_POST['pass']) ? $_POST['pass'] : null);

	$logindata = fetch("SELECT id,password FROM users WHERE name = ?", [$name]);

	if (!$name || !$pass || !$logindata || !password_verify($pass, $logindata['password'])) $error .= 'Invalid credentials.';

	if ($error == '') {
		setcookie('user', $logindata['id'], 2147483647);
		setcookie('passenc', base64_encode($pass), 2147483647);

		redirect('./');
	}
}

$twig = twigloader();
echo $twig->render('login.twig', ['error' => $error]);