<?php

/**
 * Remove all notifications for a level (e.g. when the user views their level)
 * @param $lid Level ID
 * @param $uid User ID
 */
function removeLevelNotifications($lid, $uid) {
	query("DELETE FROM notifications WHERE type = 1 AND level = ? AND recipient = ?",
		[$lid, $uid]);

	clearMentions('level', $lid);
}

/**
 * Get array of notifications for user
 * @param mixed $uid User ID
 */
function getNotifications($uid) {
	return fetchArray(query("SELECT n.*, l.id l_id, l.title l_title, @userfields
			FROM notifications n LEFT JOIN levels l ON n.level = l.id JOIN users u ON n.sender = u.id
			WHERE n.recipient = ?",
		[$uid]));
}

function prepareNotifications($notifs, $userid) {
	$notifications = [];

	foreach ($notifs as $notif) {
		switch ($notif['type']) {
			case 1:
				$notifications[] = sprintf(
					'%s commented on your level <a href="/level/%s">%s</a>.',
				userlink($notif, 'u_'), $notif['l_id'], $notif['l_title']);
			break;
			case 2:
				$notifications[] = sprintf(
					'%s commented on your <a href="/user/%s?forceuser">user page</a>.',
				userlink($notif, 'u_'), $userid);
			break;
			case 3:
				$notifications[] = sprintf(
					'%s sent you a private message: <a href="/forum/showprivate?id=%s">Read</a>',
				userlink($notif, 'u_'), $notif['level']);
			break;
			case 11:
			case 13:
			case 14:
			case 16:
				$notifications[] = sprintf(
					'%s mentioned you in a %s comment: <a href="/%s/%s">Read</a>',
				userlink($notif, 'u_'), cmtNumToType($notif['type'] - 10), cmtNumToType($notif['type'] - 10), $notif['level']);
			break;
		}
	}

	return $notifications;
}

