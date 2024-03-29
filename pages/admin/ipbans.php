<?php

if (!IS_ADMIN) error('403');

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

twigloader()->display('admin/ipbans.twig', [
	'ipbans' => $ipbans,
]);
