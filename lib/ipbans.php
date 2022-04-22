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
