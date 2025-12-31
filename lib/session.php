<?php

function initSession($userId, $log, $noUpdate = false) {
	if (!$log)
		return ['rank' => 0];

	$userdata = fetch("SELECT * FROM users WHERE id = ?", [$userId]);
	$userdata['notifications'] = result("SELECT COUNT(*) FROM notifications WHERE recipient = ?", [$userdata['id']]);

	if ($userdata['rank'] < 0)
		$userdata['banreason'] = result("SELECT reason FROM bans WHERE user = ?", [$userId]);

	if (!$noUpdate)
		updateSession($userdata);

	return $userdata;
}

function updateSession(&$userdata) {
	query("UPDATE users SET lastview = ?, ip = ? WHERE id = ?", [time(), HTTP_IP, $userdata['id']]);
	$userdata['lastview'] = time();
}
