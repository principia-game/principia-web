<?php

/**
 * Bans an IP address with principia-web's IP ban system.
 *
 * @param string $ip IPv4 address
 * @param string $reason Reason (leave blank for none)
 */
function ipBan($ip, $reason = 'N/A') {
	global $cache;
	query("INSERT INTO ipbans (ip,reason) VALUES (?,?)", [$ip, $reason]);
	$cache->set('ipb_'.$ip, $reason);
}

/**
 * Unbans an existing IP ban.
 *
 * @param string $ip IPv4 address
 */
function ipUnban($ip) {
	global $cache;
	query("DELETE FROM ipbans WHERE ip = ?", [$ip]);
	$cache->delete('ipb_'.$ip);
}

function showIpBanMsg($reason) {
	global $appealmsg;
	http_response_code(403);
	printf(<<<HTML
		<style>
		body {
			background-color: #640000;
			color: #ffdfdf;
			font-family: sans-serif;
			font-size: 16pt;
			margin: auto;
			max-width: 500px;
			padding-top: 100px;
		}
		</style>
		<p>Your IP address has been banned.</p>
		<p><strong>Reason:</strong> %s</p>
		<p>%s</p>
HTML, ($reason != 'N/A' ? $reason : '<em>No reason specified</em>'), $appealmsg);
	die();
}
