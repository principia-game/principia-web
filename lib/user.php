<?php

function getUserById($uid) {
	return fetch("SELECT * FROM @users WHERE id = ?", [$uid]);
}

function getUserByName($name) {
	return fetch("SELECT * FROM @users WHERE name = ?", [$name]);
}

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
			'<a class="user" href="/archive/user/%d"><span class="t_user">%s%s</span></a>',
		$user[$pre.'id'], $user[$pre.'name'], $trophy);
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
		$fields = array_merge($fields, ['t_black', 't_gold', 't_silver']);
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

