<?php

/**
 * Return HTML code for an userlink, including stuff like custom colors
 *
 * @param array $user User array containing user fields. Retrieve this from the database using userfields().
 * @param string $pre $user key prefix.
 * @return string Userlink HTML code.
 */
function userlink($user, $pre = '') {
	//if ($user[$pre.'id'] == 1) {
	//	$user[$pre.'name'] = '<span style="color:#D60270">ROll</span><span style="color:#9B4F96">er</span><span style="color:#0038A8">ozxa</span>';
	//}

	if ($user[$pre.'customcolor'])
		$user[$pre.'name'] = sprintf('<span style="color:#%s">%s</span>', $user[$pre.'customcolor'], $user[$pre.'name']);

	return sprintf(
		'<a class="user" href="/user/%d"><span class="t_user">%s</span></a>',
	$user[$pre.'id'], $user[$pre.'name']);
}

/**
 * Get list of SQL SELECT fields for userlinks.
 *
 * @return string String to put inside a SQL statement.
 */
function userfields($tbl = null, $pf = null) {
	$fields = ['id', 'name', 'customcolor'];
	$out = [];

	if ($tbl) {
		// Acmlmboard-like
		foreach ($fields as $f)
			$out[] = ($tbl ? $tbl.'.' : '').$f.($pf ? ' '.$pf.$f : '');
	} else {
		// Simpler, principia-web-like
		foreach ($fields as $field)
			$out[] = sprintf('u.%s u_%s', $field, $field);
	}

	return commasep($out);
}

function userfields_post() {
	$fields = ['posts', 'joined', 'lastpost', 'lastview', 'avatar', 'signature'];
	$out = [];

	foreach ($fields as $f)
		$out[] = "u.$f u$f";

	return commasep($out);
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
			case 12:
			case 13:
			case 14:
			case 16:
				$notifications[] = sprintf(
					'%s mentioned you in a %s comment: <a href="/%s/%s">Read</a>',
				userlink($notif, 'u_'), cmtNumToType($notif['type'] - 10), cmtNumToType($notif['type'] - 10), $notif['level']);
			break;
			case 15:
				$notifications[] = sprintf(
					'%s mentioned you in the chat: <a href="/chat">Read</a>',
				userlink($notif, 'u_'));
			break;
		}
	}

	return $notifications;
}
