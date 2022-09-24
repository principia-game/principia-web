<?php
require('lib/common.php');

needsLogin();

if (isset($_POST['action'])) {
	$error = '';

	$fields = [
		'about'		=> $_POST['about'] ?: null,
		'location'	=> $_POST['location'] ?: null,
		'signature'	=> $_POST['signature'] ?: null,
		'darkmode'	=> $_POST['darkmode'] ? 1 : 0,
		'timezone'	=> $_POST['timezone'] != 'Europe/Stockholm' ? $_POST['timezone'] : null
	];

	if ($userdata['powerlevel'] > 2)
		$fields['title'] = $_POST['title'] ?: null;

	if ($userdata['powerlevel'] > 1) {
		$customcolor = strtolower($_POST['customcolor']) != '#0000aa' ? $_POST['customcolor'] : null;

		// check custom color
		$customcolor = ltrim($customcolor, '#');
		if (preg_match('/([A-Fa-f0-9]{6})/', $_POST['customcolor'])) {
			// only set if valid
			$fields['customcolor'] = $customcolor;
		}
	}

	// Temp variables for dynamic query construction.
	$fieldquery = '';
	$placeholders = [];

	// Construct a query containing all fields.
	foreach ($fields as $fieldk => $fieldv) {
		if ($fieldquery) $fieldquery .= ',';
		$fieldquery .= $fieldk.'=?';
		$placeholders[] = $fieldv;
	}

	// 100% safe from SQL injection because no arbitrary user input is ever put directly
	// into the query, rather it is passed as a prepared statement placeholder.
	$placeholders[] = $userdata['id'];
	query("UPDATE users SET $fieldquery WHERE id = ?", $placeholders);

	redirect(sprintf("/user/%s?edited", $userdata['id']));
}

$timezones = [];
foreach (timezone_identifiers_list() as $tz) {
	$timezones[] = $tz;
}

$twig = twigloader();
echo $twig->render('settings.twig', [
	'timezones' => $timezones
]);
