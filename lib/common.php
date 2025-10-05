<?php
if (!file_exists('data/config.php'))
	die('Configuration file missing.');

// load profiler first
require_once('lib/profiler.php');
$profiler = new Profiler();

if (!isset($internal)) $internal = false;

require_once('data/config.php');

define('DEBUG', (isset($debug) && $debug));

if (!defined('IS_ARCHIVE'))
	define('IS_ARCHIVE', false);

if (DEBUG) {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
}

require_once('vendor/autoload.php');
foreach (glob("lib/*.php") as $file)
	require_once($file);

if (!$internal)
	securityHeaders();

if (!isCli()) {
	$precache = new PreCache($cache);

	// Shorter constants for common $_SERVER values.
	define('HTTP_IP', $_SERVER['REMOTE_ADDR']);
	define('HTTP_UA', $_SERVER['HTTP_USER_AGENT'] ?? null);
	define('HTTP_RF', $_SERVER['HTTP_REFERER'] ?? null);

	// principia-web IP ban system
	checkIpBan(HTTP_IP);
} else {
	// Dummy values for CLI usage
	define('HTTP_IP', '127.0.0.1');
	define('HTTP_UA', 'principia-web/cli (sexy, like PHP)');
	define('HTTP_RF', '');
}

$userId = authenticateCookie();
$log = $userId != -1;

if ($log) {
	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$userId]);
	$userdata['notifications'] = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);

	if ($userdata['rank'] < 0)
		$userdata['banreason'] = result("SELECT reason FROM bans WHERE user = ?", [$userId]);

	query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), HTTP_IP, $userdata['id']]);
	$userdata['lastview'] = time();
} else {
	$userdata = [
		'rank' => 0
	];
}

$userdata['darkmode'] = !isset($_COOKIE['darkmode']) || $_COOKIE['darkmode'] == 1;

define('IS_BANNED', $userdata['rank'] < 0);
define('IS_MEMBER', $userdata['rank'] > 0);
define('IS_MOD',	$userdata['rank'] > 1);
define('IS_ADMIN',	$userdata['rank'] > 2);
define('IS_ROOT',	$userdata['rank'] > 3);

if (!$log || !$userdata['timezone'])
	$userdata['timezone'] = 'Europe/Stockholm'; // I'm a self-centered egomaniac! Time itself centers around me!

date_default_timezone_set($userdata['timezone']);
