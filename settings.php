<?php
require('lib/common.php');

if (isset($_POST['magic'])) {

	$customcolor	= $_POST['customcolor'];
	$about			= $_POST['about'];
	$location		= $_POST['location'];
	$signature		= $_POST['signature'];
	$darkmode		= ($_POST['darkmode'] ? 1 : 0); // clamp it for good measure
	$timezone		= $_POST['timezone']; // should I maybe check timezone? ehh...

	// check custom color
	$customcolor = ltrim($customcolor, '#');
	if (!preg_match('/^([A-Fa-f0-9]{6})$/', $_POST['customcolor'])) {
		// reset if invalid
		$customcolor = $userdata['customcolor'];
	}

	query("UPDATE users SET customcolor = ?, about = ?, location = ?, darkmode = ?, timezone = ?, signature = ? WHERE id = ?",
		[$customcolor, $about, $location, $darkmode, $timezone, $signature, $userdata['id']]);

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