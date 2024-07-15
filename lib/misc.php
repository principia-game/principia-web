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

function offerFile($filepath, $savename) {
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"$savename\"");
	header("Content-Length: ".filesize($filepath));

	readfile($filepath);
}
