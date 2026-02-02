<?php

function getUserById($uid) {
	return fetch("SELECT * FROM @users WHERE id = ?", [$uid]);
}

function getUserByName($name) {
	return fetch("SELECT * FROM @users WHERE name = ?", [$name]);
}

/**
 * Return HTML code for an userlink, including stuff like custom colors
 *
 * @param array $user User array containing user fields.
 * @param string $pre $user key prefix.
 * @return string Userlink HTML code.
 */
function userlink($user, $pre = '', $archive = IS_ARCHIVE) {
	if ($user[$pre.'customcolor'])
		$user[$pre.'name'] = sprintf('<span style="color:#%s">%s</span>', $user[$pre.'customcolor'], $user[$pre.'name']);

	$url = sprintf($archive ? '/archive/user/%d' : '/user/%d', $user[$pre.'id']);

	return sprintf(
		'<a class="user" href="%s"><span class="t_user">%s%s</span></a>',
	$url, $user[$pre.'name'], printTrophies($user[$pre.'trophies'] ?? 0));
}

/**
 * Get list of SQL SELECT fields for userlinks.
 *
 * @return string String to put inside a SQL statement.
 */
function userfields($tbl = null, $pf = null) {
	$fields = ['id', 'name', 'customcolor', 'trophies'];
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

function avatarUrl($user, $pf = '') {
	if (!$user[$pf.'avatar'])
		return null;

	return sprintf('/userpic/%d.png?v=%d', $user[$pf.'id'], $user[$pf.'avatar']);
}
