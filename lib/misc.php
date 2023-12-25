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
		'joined' => time(),
		'darkmode' => 1
	]);

	if ($sendWelcomeEmail && false) {
		sendMail($mail, 'Welcome to principia-web!', sprintf(<<<HTML
				<p><b>Welcome to principia-web, %s!<b></p>

				<p>Principia-web is an unofficial community site replacement for the game Principia, as the official community site was shut down in early 2018.
				Feel free to upload some cool levels!</p>

				<p><em>This is an automated email, sent to you since you registered an account on principia-web.</em></p>
HTML
		, $name));
	}

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
