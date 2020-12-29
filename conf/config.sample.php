<?php
$host = '127.0.0.1';
$db   = 'principia';
$user = '';
$pass = '';

$basepath = '/';

$tplCache = 'templates/cache';
$tplNoCache = false; // **DO NOT SET AS TRUE IN PROD - DEV ONLY**

$lpp = 20;

// Disable this if you don't have a sense of humor.
$iHaveASenseOfHumor = true;

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

// Stub function to put special information in the footer.
function customInfo() { }