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
