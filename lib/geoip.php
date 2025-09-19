<?php

$geoip = new MaxMind\Db\Reader('data/GeoLite2-Country.mmdb');

function getUserCountry() {
	global $geoip, $ipaddr;

	return $geoip->get($ipaddr)['country']['iso_code'] ?? 'AQ';
}
