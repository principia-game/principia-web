#!/usr/bin/php
<?php

print("principia-web tools - check-ips.php\n");
print("=========================================\n");
print("This script checks a list of IP addresses to see if any principia-web user has the specified IP address, intended to be used for preventing innocent people being caught in firewall IP blocks.\n\n");

require('lib/common.php');

$ips = explode("\n", file_get_contents($argv[1]));

foreach ($ips as $ip) {
	if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ip])) {
		printf("The IP '%s' exists in the database!\n", $ip);
	}
}
