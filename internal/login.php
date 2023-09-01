<?php
if (isset($_POST['cPa1Ozi']) && $_POST['cPa1Ozi'] == "Submit  ") {
	if (!isset($_POST['username']) || !isset($_POST['password']))
		die('102');

	$logindata = fetch("SELECT id, name, password, token FROM users WHERE name = ?", [$_POST['username']]);

	if (password_verify($_POST['password'], $logindata['password'])) {
		$notificationCount = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$logindata['id']]);

		header("X-Principia-User-Id: ".$logindata['id']);
		header("X-Principia-User-Name: ".$logindata['name']);
		header("X-Principia-Unread: $notificationCount");

		setcookie($cookieName, $logindata['token'], time() + 3600*24*365, '/');

		echo '100'; // Logged in successfully
	} else
		echo '103'; // Invalid username or password

} else
	echo '101'; // An error occured when trying to log in
