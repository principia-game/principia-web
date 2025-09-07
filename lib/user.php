<?php

function tcount($c) {
	return $c > 1 ? '<span class="num_trophees">'.$c.'</span>' : '';
}

/**
 * Return HTML code for an userlink, including stuff like custom colors
 *
 * @param array $user User array containing user fields.
 * @param string $pre $user key prefix.
 * @return string Userlink HTML code.
 */
function userlink($user, $pre = '') {
	//if ($user[$pre.'id'] == 1)
	//	$user[$pre.'name'] = '<span style="color:#D60270">ROll</span><span style="color:#9B4F96">er</span><span style="color:#0038A8">ozxa</span>';

	if ($user[$pre.'customcolor'])
		$user[$pre.'name'] = sprintf('<span style="color:#%s">%s</span>', $user[$pre.'customcolor'], $user[$pre.'name']);

	if (IS_ARCHIVE) {
		$trophy = '';
		$black = $user[$pre.'t_black'] ?? 0;
		$gold = $user[$pre.'t_gold'] ?? 0;
		$silver = $user[$pre.'t_silver'] ?? 0;
		if ($black) $trophy .= '<span class="trophee bdiamond">'.tcount($black).'</span>';
		if ($gold) $trophy .= '<span class="trophee gold">'.tcount($gold).'</span>';
		if ($silver) $trophy .= '<span class="trophee silver">'.tcount($silver).'</span>';

		return sprintf(
			'<a class="user" href="/user/%d"><span class="t_%s">%s%s</span></a>',
		$user[$pre.'id'], powIdToName($user[$pre.'rank']), $user[$pre.'name'], $trophy);
	}

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
	if (IS_ARCHIVE) {
		$fields = array_merge($fields, ['rank', 't_black', 't_gold', 't_silver']);
	}
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
