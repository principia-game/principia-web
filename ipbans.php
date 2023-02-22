<?php
require('lib/common.php');

// From the wiki, put this into the main codebase sometime mm?
function normalise($text) {
	// I HATE CRLF I HATE CRLF
	return trim(str_replace("\r", "", $text));
}

if ($userdata['rank'] < 3) error('403', 'You don\'t have access to this page.');

if (isset($_POST['action'])) {
	$iplist = $_POST['iplist'] ?? null;
	$reason = $_POST['reason'] ?? null;

	// Get list of IP addresses
	$ips = explode("\n", normalise($iplist));

	foreach ($ips as $ip) {
		ipBan(normalise($ip), $reason);
	}
}

$ipbans = query("SELECT * FROM ipbans");

echo twigloader()->render('ipbans.twig', [
	'ipbans' => $ipbans,
]);
