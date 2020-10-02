<?php
if (!file_exists('conf/config.php')) {
	die('Please read the installing instructions in the README file.');
}

$start = microtime(true);

require('conf/config.php');

require('vendor/autoload.php');

require('lib/layout.php');
require('lib/level.php');
require('lib/mysql.php');
require('lib/twig.php');
require('lib/user.php');

date_default_timezone_set('GMT');

// Authentication code.
if (!empty($_COOKIE['user']) || !empty($_COOKIE['passenc'])) {
	$pass_db = result("SELECT password FROM users WHERE id = ?", [$_COOKIE['user']]);

	if (password_verify(base64_decode($_COOKIE['passenc']), $pass_db)) {
		// Valid password cookie.
		$log = true;
	} else {
		// Invalid password cookie.
		$log = false;
	}
} else {
	// No password cookie.
	$log = false;
}

if ($log) {
	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$_COOKIE['user']]);
} else {
	// This should be the place for default options for logged out users,
	// but at the moment, it's empty.
}
