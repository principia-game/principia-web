<?php

function internalAuth() {
	global $log, $userdata, $notificationCount;

	if ($log) {
		header("X-Principia-User-Id: ".$userdata['id']);
		if (isset($_SERVER['HTTPS']))
			header("X-Principia-User-Name: ".$userdata['name']."|UPDATE!!");
		else
			header("X-Principia-User-Name: ".$userdata['name']);
		header("X-Principia-Unread: ".$notificationCount);
	}
}

/**
 * Checks that the token is valid.
 */
function validToken($token) {
	// Tokens are 40 characters in length (Why? Apparatus. I really should make them longer...)
	return strlen($token) == 40 && ctype_xdigit($token);
}
