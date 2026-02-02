<?php

needsLogin();

if (isset($_POST['resetpassword'])) {
	$password = $_POST['password'] ?? null;

	$correctpass = password_verify($password, $userdata['password']);

	if ($correctpass) {
		$tok = generatePasswordReset($userdata['id']);

		redirect("/resetpassword?id=%s", $tok);
	} else
		$error = 'Current password inputted was invalid.';
}

if (isset($_POST['action'])) {
	$error = '';

	$fields = [
		'pronouns'		=> $_POST['pronouns'] ?: null,
		'about'			=> $_POST['about'] ?: null,
		'signature'		=> $_POST['signature'] ?: null,
		'archivename'	=> $_POST['archivename'] ?: null,
		'timezone'		=> $_POST['timezone'] != 'Europe/Stockholm' ? $_POST['timezone'] : null
	];

	if (IS_MOD) {
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
			if (move_uploaded_file($fname['tmp_name'], 'data/userpic/'.$userdata['id'].'.png'))
				$fields['avatar'] = time();
			else
				trigger_error("Avatar uploading broken, check data/userpic/ permissions", E_USER_ERROR);
		}
	}

	$update = updateRowQuery($fields);
	$update['placeholders'][] = $userdata['id'];
	query("UPDATE users SET ".$update['fieldquery']." WHERE id = ?", $update['placeholders']);

	redirect("/user/%s?edited", $userdata['id']);
}

$timezones = [];
foreach (timezone_identifiers_list() as $tz)
	$timezones[] = $tz;

twigloader()->display('settings.twig', [
	'error' => $error ?? null,
	'timezones' => $timezones
]);
