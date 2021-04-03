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

	if ($user[$pre.'customcolor']) {
		$user[$pre.'name'] = sprintf('<span style="color:#%s">%s</span>', $user[$pre.'customcolor'], $user[$pre.'name']);
	}

	return <<<HTML
		<a class="user" href="user.php?id={$user[$pre.'id']}"><span class="t_user">{$user[$pre.'name']}</span></a>
HTML;
}

/**
 * Get list of SQL SELECT fields for userlinks.
 *
 * @return string String to put inside a SQL statement.
 */
function userfields() {
	$fields = ['id', 'name', 'customcolor'];

	$out = '';
	foreach ($fields as $field) {
		$out .= sprintf('u.%s u_%s,', $field, $field);
	}

	return $out;
}