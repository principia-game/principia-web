<?php
$host = '127.0.0.1';
$db   = 'principia';
$user = '';
$pass = '';

$db_forum = 'principia_forum';

$basepath = '/';

$tplCache = 'templates/cache';
$tplNoCache = false; // **DO NOT SET AS TRUE IN PROD - DEV ONLY**

$lpp = 20;

// Disable this if you don't have a sense of humor.
$iHaveASenseOfHumor = true;

// Redirect all non-internal pages to https.
$https = true;

// Cookie token name. Don't change this too often as it'll invalidate old logins!
$cookieName = 'token';

// Website domain.
$domain = 'https://example.org';

// Discord server link. If blank will disable Discord link.
$invite = '';

// URL to Discord webhook for new level uploads. Leave blank to disable this.
$webhook = '';

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

// Email stuff. Uncomment to enable
//$email['host'] = '';
//$email['port'] = '';
//$email['username'] = '';
//$email['password'] = '';

// Stub function to put special information in the footer.
function customInfo() { }


/// GENERAL FORUM STUFF

$forumEnabled = true; // Enable forum-related stuff

$trashid = 2; // Designates the id for your trash forum.

// Random forum descriptions.
// It will be replacing the value %%%RANDOM%%% in the forum description.
$randdesc = [
	"Value1",
	"Value2"
];