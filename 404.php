<?php
require('lib/common.php');

$error = (isset($_GET['error']) ? $_GET['error'] : 404);

switch ($error) {
	case 403:
		echo error('403', "Hey, no touching! No looking either!");
	break;
	case 404:
	default:
		echo error('404', "The requested page wasn't found.");
	break;
}
