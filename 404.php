<?php
require('lib/common.php');

$error = $_GET['error'] ?? 404;

switch ($error) {
	case 403:
		error('403', "Hey, no touching! No looking either!");
	break;
	case 404:
	default:
		if (str_starts_with($uri, '/apZodIaL1')) {
			ipBan($ipaddr, 'Touching internal pages.');
		}
		error('404', "The requested page wasn't found.");
	break;
}
