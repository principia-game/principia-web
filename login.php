<?php
include('lib/common.php');

if (isset($_POST['logout'])) {
	setcookie('user', '');
	setcookie('passenc', '');
	redirect('./');
}

if ($log) redirect('./');

if (isset($_POST['action'])) {
	$logindata = fetch("SELECT id,password FROM users WHERE name = ?", [$_POST['name']]);

	if (password_verify($_POST['pass'], $logindata['password'])) {
		setcookie('user', $logindata['id'], 2147483647);
		setcookie('passenc', base64_encode($_POST['pass']), 2147483647);
	}
	redirect('./');
}

$twig = twigloader();
echo $twig->render('login.twig');