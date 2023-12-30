<?php

function internalAuth() {
	global $log, $userdata, $notificationCount;

	if ($log) {
		header("X-Principia-User-Id: ".$userdata['id']);
		header("X-Principia-User-Name: ".$userdata['name']);
		header("X-Principia-Unread: ".$userdata['notifications']);
	}
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
