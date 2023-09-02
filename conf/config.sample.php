<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'principia');
define('DB_USER', '');
define('DB_PASS', '');

define('TPL_CACHE', 'templates/cache');
define('TPL_NO_CACHE', false);

define('EMAIL_SALT', 'CHANGEME'); // Email salt to prevent rainbow table attacks. CHANGE THIS!

define('LPP', 20); // Levels per page
define('PPP', 20); // Forum posts per page
define('TPP', 20); // Forum threads per page

define('COOKIE_NAME', '_PRINCSECURITY');

define('DOMAIN', 'http://principia-web.uwu');

define('WEBHOOK_LEVEL', '');
define('WEBHOOK_WIKI', '');
define('WEBHOOK_FORUM', '');

// principia-web's CAPTCHA system relies on a list of security questions. It's highly recommended to change these.
// The answer checking is case-insensitive, but it has to be written in all lowercase here.
$captcha = [
	1 => [
		'question' => 'Are you a bot?',
		'answer' => ['no']
	], 2 => [
		'question' => 'Are you a human?',
		'answer' => ['yes']
	]
];

function customInfo() { }

function customHeader() { }

$footerlinks = [
	'/about' => 'About'
];

$chatmsg = 'Please use the <a href="forum/">forum</a> for bigger discussions and questions.';

/// GENERAL FORUM STUFF

$trashid = 2; // Designates the id for your trash forum.
