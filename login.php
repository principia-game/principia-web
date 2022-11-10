<?php
require('lib/common.php');

if (isset($_POST['logout'])) {
	setcookie($cookieName, '');
	redirect('./');
}

if ($log) redirect('./');

$error = '';

if (isset($_GET['resetted'])) $error .= 'Password successfully reset! Please login with your new password.';

if (isset($_POST['action'])) {
	$name = $_POST['name'] ?? null;
	$pass = $_POST['pass'] ?? null;

	$logindata = fetch("SELECT id,password,token FROM users WHERE name = ?", [$name]);

	if (!$name || !$pass || !$logindata || !password_verify($pass, $logindata['password'])) $error .= 'Invalid credentials.';

	if ($error == '') {
		setcookie($cookieName, $logindata['token'], 2147483647);

		redirect('./');
	}
}

echo twigloader()->render('login.twig', ['error' => $error]);