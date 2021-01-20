<?php
require('lib/common.php');

if (isset($_POST['magic'])) {
	$title			= $_POST['title'] ? $_POST['title'] : null;
	$customcolor	= strtolower($_POST['customcolor']) != '#0000aa' ? $_POST['customcolor'] : null;
	$about			= $_POST['about'] ? $_POST['about'] : null;
	$location		= $_POST['location'] ? $_POST['location'] : null;
	$signature		= $_POST['signature'] ? $_POST['signature'] : null;
	$darkmode		= $_POST['darkmode'] ? 1 : 0; // clamp it for good measure
	$timezone		= $_POST['timezone'] != 'Europe/Stockholm' ? $_POST['timezone'] : null;

	// check custom color
	$customcolor = ltrim($customcolor, '#');
	if (!preg_match('/^([A-Fa-f0-9]{6})$/', $_POST['customcolor'])) {
		// reset if invalid
		$customcolor = $userdata['customcolor'];
	}

	query("UPDATE users SET title = ?, customcolor = ?, about = ?, location = ?, darkmode = ?, timezone = ?, signature = ? WHERE id = ?",
		[$title, $customcolor, $about, $location, $darkmode, $timezone, $signature, $userdata['id']]);

	redirect(sprintf("user.php?id=%s&edited", $userdata['id']));
}

$timezones = [];
foreach (timezone_identifiers_list() as $tz) {
	$timezones[] = $tz;
}

$twig = twigloader();
echo $twig->render('settings.twig', [
	'timezones' => $timezones
]);