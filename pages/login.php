<?php
if (isset($_POST['logout'])) {
	setcookie(COOKIE_NAME, '');
	redirect('./');
}

if ($log) redirect('./?al');

$error = '';

if (isset($_GET['resetted'])) $error .= 'Password successfully reset! Please login with your new password.';

$name = $_POST['name'] ?? null;

if (isset($_POST['action'])) {
	$pass = $_POST['pass'] ?? null;

	$logindata = fetch("SELECT id,password,token FROM users WHERE name = ?", [$name]);

	if (!$name || !$pass || !$logindata || !password_verify($pass, $logindata['password'])) $error .= 'Invalid credentials.';

	if ($error == '') {
		setcookie(COOKIE_NAME, $logindata['token'], 2147483647);

		redirect('./');
	}
}

twigloader()->display('login.twig', [
	'error' => $error,
	'name' => $name
]);
