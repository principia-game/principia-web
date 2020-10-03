<?php
include('lib/common.php');

if (isset($_POST['action'])) {
	$logindata = fetch("SELECT id,password FROM users WHERE name = ?", [$_POST['name']]);

	if (password_verify($_POST['pass'], $logindata['password'])) {
		setcookie('user', $logindata['id']);
		setcookie('passenc', base64_encode($_POST['pass']));
	}
	header('Location: ./');
}

$twig = twigloader();
echo $twig->render('login.twig');