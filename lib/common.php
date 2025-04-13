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

if (!$internal)
	securityHeaders();

$userfields = userfields();

if (!isCli()) {
	// Shorter variables for common $_SERVER values.
	$ipaddr = $_SERVER['REMOTE_ADDR'];
	$useragent = $_SERVER['HTTP_USER_AGENT'] ?? null;
	$referer = $_SERVER['HTTP_REFERER'] ?? null;

	// principia-web IP ban system
	checkIpBan($ipaddr);
} else {
	// Dummy values for CLI usage
	$ipaddr = '127.0.0.1';
	$useragent = 'principia-web/cli (sexy, like PHP)';
	$referer = '';
}

$userId = authenticateCookie();
$log = $userId != -1;

if ($log) {
	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$userId]);
	$userdata['notifications'] = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);

	if ($userdata['rank'] < 0)
		$userdata['banreason'] = result("SELECT reason FROM bans WHERE user = ?", [$userId]);

	query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), $ipaddr, $userdata['id']]);
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
