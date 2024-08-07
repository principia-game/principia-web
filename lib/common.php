<?php
if (!file_exists('conf/config.php'))
	die('Please read the installing instructions in the README file.');

// load profiler first
require_once('lib/profiler.php');
$profiler = new Profiler();

if (!isset($internal)) $internal = false;

require_once('conf/config.php');

define('DEBUG', (isset($debug) && $debug));

if (DEBUG) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

require_once('vendor/autoload.php');
foreach (glob("lib/*.php") as $file)
	require_once($file);

if (!$internal) {
	// Security headers.
	header("Content-Security-Policy:"
		."default-src 'self';"
		."script-src 'self' 'unsafe-inline';"
		."img-src 'self' data: *.voxelmanip.se voxelmanip.se *.imgur.com imgur.com *.github.com github.com *.githubusercontent.com cdn.discordapp.com;"
		."media-src 'self' *.voxelmanip.se voxelmanip.se;"
		."frame-src *.youtube-nocookie.com;"
		."style-src 'self' 'unsafe-inline';");

	header("Referrer-Policy: strict-origin-when-cross-origin");
	header("X-Content-Type-Options: nosniff");
	header("X-Frame-Options: SAMEORIGIN");
	header("X-Xss-Protection: 1; mode=block");
}

$userfields = userfields();

if (!isCli()) {
	// Shorter variables for common $_SERVER values.
	$ipaddr = $_SERVER['REMOTE_ADDR'];
	$useragent = $_SERVER['HTTP_USER_AGENT'] ?? null;
	$referer = $_SERVER['HTTP_REFERER'] ?? null;

	// principia-web IP ban system
	$ipban = $cache->get('ipb_'.$ipaddr);
	if ($ipban) {
		if (str_starts_with($ipban, "[silent]"))
			die();
		else
			showIpBanMsg($ipban);
	}
} else {
	// Dummy values for CLI usage
	$ipaddr = '127.0.0.1';
	$useragent = 'principia-web/cli (sexy, like PHP)';
	$referer = '';
}

// Authentication code.
$log = false;

if (isset($_COOKIE[COOKIE_NAME]) && validToken($_COOKIE[COOKIE_NAME])) {
	$id = result("SELECT id FROM users WHERE token = ?", [$_COOKIE[COOKIE_NAME]]);

	if ($id) // Valid cookie, user is logged in.
		$log = true;
}

if ($log) {
	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$id]);
	$userdata['notifications'] = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);

	if (!$internal) {
		if ($userdata['rank'] < 0)
			$userdata['banreason'] = result("SELECT reason FROM bans WHERE user = ?", [$id]);

		query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), $ipaddr, $userdata['id']]);
		$userdata['lastview'] = time();
	}
} else {
	$userdata = [
		'rank' => 0
	];
}

$userdata['darkmode'] = (isset($_COOKIE['darkmode']) ? $_COOKIE['darkmode'] == 1 : true);

define('IS_BANNED', $userdata['rank'] < 0);
define('IS_MEMBER', $userdata['rank'] > 0);
define('IS_MOD',	$userdata['rank'] > 1);
define('IS_ADMIN',	$userdata['rank'] > 2);
define('IS_ROOT',	$userdata['rank'] > 3);

if (!$log || !$userdata['timezone'])
	$userdata['timezone'] = 'Europe/Stockholm'; // I'm a self-centered egomaniac! Time itself centers around me!

date_default_timezone_set($userdata['timezone']);
