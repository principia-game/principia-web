<?php

function authenticateCookie() {

	if (isset($_COOKIE[COOKIE_NAME]) && validToken($_COOKIE[COOKIE_NAME])) {
		$id = result("SELECT id FROM users WHERE token = ?", [$_COOKIE[COOKIE_NAME]]);

		if ($id) // Valid cookie, user is logged in.
			return $id;
	}

	return -1;
}

function internalAuth() {
	global $log, $userdata;

	if ($log)
		sendUserHeaders($userdata['id'], $userdata['name'], $userdata['notifications']);
}

/**
 * Checks that the token is valid.
 */
function validToken($token) {
	// Tokens are 40 characters in length (Why? Apparatus. I really should make them longer...)
	return strlen($token) == 40 && ctype_xdigit($token);
}

function internalKey() {
	return isset($_POST['key']) && $_POST['key'] == 'cuddles';
}
