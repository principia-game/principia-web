<?php
if (!file_exists('conf/config.php')) {
	die('Please read the installing instructions in the README file.');
}

// load profiler first
require_once('lib/profiler.php');
$profiler = new Profiler();

require_once('conf/config.php');
require_once('vendor/autoload.php');
foreach (glob("lib/*.php") as $file) {
	require_once($file);
}

if (!empty($blockedUA) && isset($_SERVER['HTTP_USER_AGENT'])) {
	foreach ($blockedUA as $bl) {
		if (str_contains($_SERVER['HTTP_USER_AGENT'], $bl)) {
			http_response_code(403);
			echo '403';
			die();
		}
	}
}

// Do redirects if this is a non-internal
if (!isCli() && !str_contains($_SERVER['SCRIPT_NAME'], 'internal')) {
	// Redirect all non-internal pages on the old domain to new domain if old domain is defined.
	if (isset($oldDomain) && $_SERVER['HTTP_HOST'] == $oldDomain) {
		header("Location: " . $domain . $_SERVER["REQUEST_URI"], true, 301);
		die();
	}

	// Redirect all non-internal pages to https if https is enabled.
	if ($https && !isset($_SERVER['HTTPS'])) {
		header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
		die();
	}

	$ipaddr = $_SERVER['REMOTE_ADDR'];
}

if (!isset($acmlm))
	$userfields = userfields();

if (!isCli()) {
	if ($cache->enabled) { // Speedy memcachelez
		$ipban = $cache->get('ipb_'.$ipaddr);
	} else { // Fallback
		$ipban = result("SELECT reason FROM ipbans WHERE ? LIKE ip", [$ipaddr]);
	}

	if ($ipban) {
		http_response_code(403);

		printf(
			"<p>Your IP address has been banned.</p>".
			"<p><strong>Reason:</strong> %s</p>",
		($ipban != 'N/A' ? $ipban : '<em>No reason specified</em>'));

		die();
	}
}

// Unset legacy authentication cookies for security purposes. This will hopefully delete it from browsers and clients.
if (isset($_COOKIE['user']))	setcookie('user', 'DEPRECATED', 1, '/');
if (isset($_COOKIE['passenc'])) setcookie('passenc', 'DEPRECATED', 1, '/');

// Authentication code.
if (isset($_COOKIE[$cookieName])) {
	$id = result("SELECT id FROM users WHERE token = ?", [$_COOKIE[$cookieName]]);

	if ($id) {
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
	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$id]);
	$notificationCount = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);

	if ($userdata['powerlevel'] < 0) {
		$userdata['banreason'] = result("SELECT reason FROM bans WHERE user = ?", [$id]);
	}

	query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), $ipaddr, $userdata['id']]);
} else {
	$userdata['powerlevel'] = 0;
	$userdata['darkmode'] = $darkModeDefault;
}

if (!$log || !$userdata['timezone'])
	$userdata['timezone'] = 'Europe/Stockholm'; // I'm a self-centered egomaniac! Time itself centers around me!

date_default_timezone_set($userdata['timezone']);
