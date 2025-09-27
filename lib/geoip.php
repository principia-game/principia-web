<?php

$geoip = new MaxMind\Db\Reader('data/GeoLite2-Country.mmdb');

function getUserCountry() {
	global $geoip;

	return $geoip->get(HTTP_IP)['country']['iso_code'] ?? 'AQ';
}
