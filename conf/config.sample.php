<?php
$host = '127.0.0.1';
$db   = 'principia';
$user = '';
$pass = '';

$tplCache = 'templates/cache';
$tplNoCache = false; // **DO NOT SET AS TRUE IN PROD - DEV ONLY**

// Array of memcached server(s) for memcache caching. Leave empty to disable memcache caching.
$memcachedServers = [];

$emailsalt = 'CHANGEME'; // Email salt to prevent rainbow table attacks. CHANGE THIS!

$lpp = 20;

// Should dark mode be default?
$darkModeDefault = true;

// Redirect all non-internal pages to https.
$https = true;

// Cookie token name. Don't change this too often as it'll invalidate old logins!
$cookieName = 'token';

// Website domain.
$domain = 'https://example.org';

// Discord server link. If blank will disable Discord link.
$invite = '';

// URL to Discord webhook for new level uploads. Leave blank to disable this.
$webhookLevel = '';

// URL to Discord webhook for wiki edits. Leave blank to disable this.
$webhookWiki = '';

// URL to Discord webhook for forum posts. Leave blank to disable this.
$webhookForum = '';

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

// List of UA fragments to block.
$blockedUA = [];

// Message to be shown on IP ban page for appeals.
$appealmsg = "If you believe this was in error, please hug a plushie and then send me a fax.";

// Email stuff. Uncomment to enable
//$email['host'] = '';
//$email['port'] = '';
//$email['username'] = '';
//$email['password'] = '';

// Stub function to put special information in the footer.
function customInfo() { }

// Stub function to put special information in the header.
function customHeader() { }

$footerlinks = [
	'/about' => 'About'
];

$chatmsg = 'Please use the <a href="forum/">forum</a> for bigger discussions and questions.';

/// GENERAL FORUM STUFF

$trashid = 2; // Designates the id for your trash forum.
