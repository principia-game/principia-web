<?php

/**
 * Returns true if it is executed from the command-line. (For command-line tools)
 */
function isCli() {
	return php_sapi_name() == "cli";
}

function register($name, $pass, $mail, $sendWelcomeEmail = true) {
	global $darkModeDefault;
	$token = bin2hex(random_bytes(20));
	query("INSERT INTO users (name, password, email, token, joined, darkmode) VALUES (?,?,?,?,?,?)",
		[$name,password_hash($pass, PASSWORD_DEFAULT), mailHash($mail), $token, time(), ($darkModeDefault ? 1 : 0)]);

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

/**
 * Get hash of latest git commit
 *
 * @param bool $trim Trim the hash to the first 7 characters
 * @return void
 */
function gitCommit($trim = true) {
	global $acmlm;

	$prefix = ($acmlm ? '../' : '');

	$commit = file_get_contents($prefix.'.git/refs/heads/master');

	if ($trim)
		return substr($commit, 0, 7);
	else
		return rtrim($commit);
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
