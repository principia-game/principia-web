<?php

/**
 * Returns true if it is executed from the command-line. (For command-line tools)
 */
function isCli() {
	return php_sapi_name() == "cli";
}

function register($name, $pass, $mail, $ip = null, $sendWelcomeEmail = true) {
	$token = bin2hex(random_bytes(20));
	insertInto('users', [
		'name' => $name,
		'password' => password_hash($pass, PASSWORD_DEFAULT),
		'ip' => $ip,
		'email' => mailHash($mail),
		'token' => $token,
		'joined' => time()
	]);

	return $token;
}

function clearMentions($type, $id) {
	global $log, $userdata;

	if ($log) {
		query("DELETE FROM notifications WHERE type = ? AND level = ? AND recipient = ?", [cmtTypeToNum($type) + 10, $id, $userdata['id']]);
	}
}

function clamp($current, $min, $max) {
	return max($min, min($max, $current));
}

function commasep($str) {
	return implode(',', $str);
}

function normalise($text) {
	// I HATE CRLF I HATE CRLF
	return trim(str_replace("\r", "", $text));
}

function esc($text) {
	return htmlspecialchars($text);
}

function renderPlaintext($filename) {
	header('Content-Type: text/plain');
	readfile($filename);
	die();
}

/**
 * Tries to convert an IPv4-mapped IPv6 address to real IPv4 address,
 * no-op if it's an actual IPv6 address.
 */
function ipv6_to_ipv4($ip) {
	$bin = inet_pton($ip);
	$prefix = substr($bin, 0, 12);

	// Check prefix for IPv4-mapped IPv6 address
	if ($prefix === "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff")
		// IPv4-mapped IPv6 address, do fun stuff with it
		return inet_ntop(substr($bin, 12));

	// Real IPv6 address, return as-is
	return $ip;
}

/**
 * Generate an alphanumeric case sensitive string used for unique non-guessable IDs
 */
function generateId($length = 10) {
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$max = strlen($alphabet) - 1;
	$id = '';
	for ($i = 0; $i < $length; $i++)
		$id .= $alphabet[random_int(0, $max)];

	return $id;
}

function possessive($name) {
	if (str_ends_with($name, 's'))
		return $name . "'";
	else
		return $name . "'s";
}
