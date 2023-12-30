<?php

$modern = ($uri == "/internal/login");

if (($modern && internalKey()) || (isset($_POST['cPa1Ozi']))) {

	if (!isset($_POST['username']) || !isset($_POST['password'])) {
		if ($modern)
			header("x-error-message: Fill the Username and Password field before pressing Log in.");
		else
			echo '102';

		die();
	}

	$logindata = fetch("SELECT id, name, password, token FROM users WHERE name = ?", [$_POST['username']]);

	if ($logindata && password_verify($_POST['password'], $logindata['password'])) {
		$notificationCount = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$logindata['id']]);

		header("x-principia-user-id: ".$logindata['id']);
		header("x-principia-user-name: ".$logindata['name']);
		header("x-principia-unread: ".$notificationCount);

		setcookie(COOKIE_NAME, $logindata['token'], time() + 3600*24*365, '/');

		if ($modern)
			header('x-notify-message: Logged in successfully!');
		else
			echo '100';
	} else {
		if ($modern)
			header("x-error-message: Invalid username or password");
		else
			echo '103';
	}
} else {
	if ($modern)
		header("x-error-message: An error occured when trying to log in.");
	else
		echo '101';
}
