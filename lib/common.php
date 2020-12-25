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

$ipban = fetch("SELECT * FROM ipbans WHERE ? LIKE ip", [$_SERVER['REMOTE_ADDR']]);
if ($ipban) {
	http_response_code(403);

	printf(
		"<p>Your IP adress has been banned.</p>".
		"<p><strong>Reason:</strong> %s</p>".
		"<p>If you believe this is in error, send an email to %s to appeal.</p>",
	$ipban['reason'], $_SERVER['SERVER_ADMIN']);

	die();
}

// Authentication code.
if (isset($_COOKIE['user']) || isset($_COOKIE['passenc'])) {
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
