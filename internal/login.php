<?php
chdir('../');
include('lib/common.php');

if (isset($_POST['cPa1Ozi']) && $_POST['cPa1Ozi'] == "Submit  ") {
	if (!isset($_POST['username']) || !isset($_POST['password'])){
		die('102');
	}

	$logindata = fetch("SELECT id, password FROM users WHERE name = ?", [$_POST['username']]);

	if (password_verify($_POST['password'], $logindata['password'])) {
		echo '100'; // Logged in successfully
	} else {
		echo '103'; // Invalid username or password
	}
} else {
	echo '101'; // An error occured when trying to log in
}

//TODO: implement error code 104: "You have reached the maximum amount of login attempts. Please wait."