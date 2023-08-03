<?php
require('lib/common.php');

needsLogin();

if (isset($_POST['action'])) {
	$error = '';

	$fields = [
		'pronouns'		=> $_POST['pronouns'] ?: null,
		'about'			=> $_POST['about'] ?: null,
		'location'		=> $_POST['location'] ?: null,
		'signature'		=> $_POST['signature'] ?: null,
		'archivename'	=> $_POST['archivename'] ?: null,
		'darkmode'		=> $_POST['darkmode'] ? 1 : 0,
		'timezone'		=> $_POST['timezone'] != 'Europe/Stockholm' ? $_POST['timezone'] : null
	];

	if ($userdata['rank'] > 2)
		$fields['title'] = $_POST['title'] ?: null;

	if ($userdata['rank'] > 1) {
		$customcolor = strtolower($_POST['customcolor']) != '#0000aa' ? $_POST['customcolor'] : null;

		// check custom color
		$customcolor = ltrim($customcolor, '#');
		if (preg_match('/([A-Fa-f0-9]{6})/', $_POST['customcolor'])) {
			// only set if valid
			$fields['customcolor'] = $customcolor;
		}
	}

	// avatars
	$fname = $_FILES['avatar'] ?? null;
	if ($fname && $fname['size'] > 0) {
		$res = getimagesize($fname['tmp_name']);

		if ($res['mime'] != 'image/png')
			$error .= "- Only PNG images allowed.<br>";

		if ($res[0] > 180 || $res[1] > 180)
			$error .= "- The image is too big.<br>";

		if ($fname['size'] > 80*1024)
			$error .= "- The image filesize too big.<br>";

		if (!$error) {
			if (move_uploaded_file($fname['tmp_name'], 'data/userpic/'.$userdata['id']))
				$fields['avatar'] = 1;
			else
				trigger_error("Avatar uploading broken, check data/userpic/ permissions", E_USER_ERROR);
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
foreach (timezone_identifiers_list() as $tz)
	$timezones[] = $tz;

echo twigloader()->render('settings.twig', [
	'timezones' => $timezones
]);
