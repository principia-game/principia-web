<?php

function internalAuth() {
	global $log, $userdata, $notificationCount;

	if ($log) {
		header("X-Principia-User-Id: ".$userdata['id']);
		header("X-Principia-User-Name: ".$userdata['name']);
		header("X-Principia-Unread: ".$notificationCount);
	}
}
