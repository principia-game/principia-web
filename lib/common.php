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

// Security headers.
header("Content-Security-Policy:"
	."default-src 'self';"
	."script-src 'self' 'unsafe-inline';"
	."img-src 'self' data: *.voxelmanip.se voxelmanip.se *.imgur.com imgur.com *.github.com github.com *.githubusercontent.com;"
	."frame-src *.youtube-nocookie.com;"
	."style-src 'self' 'unsafe-inline';");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-Xss-Protection: 1; mode=block");

if (!isset($acmlm))
	$userfields = userfields();

if (!isCli()) {
	// Shorter variables for common $_SERVER values.
	$ipaddr = $_SERVER['REMOTE_ADDR'];
	$useragent = $_SERVER['HTTP_USER_AGENT'] ?? null;
	$uri = $_SERVER['REQUEST_URI'] ?? null;
	$referer = $_SERVER['HTTP_REFERER'] ?? null;

	// UA-based bans, for dumb and identifiable scripts
	if (!empty($blockedUA) && $useragent) {
		foreach ($blockedUA as $bl) {
			if (str_contains($useragent, $bl)) {
				http_response_code(403);
				echo '403';
				die();
			}
		}
	}

	// Redirect all non-internal pages to https if https is enabled.
	if ($https && !isset($_SERVER['HTTPS']) && !isset($_COOKIE['force-http'])) {
		header("Location: https://".$_SERVER["HTTP_HOST"].$uri, true, 301);
		die();
	}

	// principia-web IP ban system
	if ($cache->enabled) // Speedy memcachelez
		$ipban = $cache->get('ipb_'.$ipaddr);
	else // Fallback
		$ipban = result("SELECT reason FROM ipbans WHERE ? LIKE ip", [$ipaddr]);

	if ($ipban)
		showIpBanMsg($ipban);
} else {
	// Dummy values for CLI usage
	$ipaddr = '127.0.0.1';
	$useragent = 'principia-web/cli (sexy, like PHP)';
	$uri = '/';
	$referer = '';
}

// Authentication code.
if (isset($_COOKIE[$cookieName]) && validToken($_COOKIE[$cookieName])) {
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

	if (!str_contains($_SERVER['SCRIPT_NAME'], 'internal')) {
		if ($userdata['powerlevel'] < 0) {
			$userdata['banreason'] = result("SELECT reason FROM bans WHERE user = ?", [$id]);
		}

		query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), $ipaddr, $userdata['id']]);
		$userdata['lastview'] = time();
	}
} else {
	$userdata['powerlevel'] = 0;
	$userdata['darkmode'] = $darkModeDefault;
}

if (!$log || !$userdata['timezone'])
	$userdata['timezone'] = 'Europe/Stockholm'; // I'm a self-centered egomaniac! Time itself centers around me!

date_default_timezone_set($userdata['timezone']);
