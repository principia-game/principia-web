<?php

function timeunits($sec) {
	if ($sec < 60)		return "$sec sec.";
	if ($sec < 3600)	return floor($sec / 60) . ' min.';
	if ($sec < 86400)	return floor($sec / 3600) . ' hour' . ($sec >= 7200 ? 's' : '');
	return floor($sec / 86400) . ' day' . ($sec >= 172800 ? 's' : '');
}

function timelink($timex, $file) {
	global $time;
	return ($time == $timex ? timeunits($timex) : "<a href=\"$file.php?time=$timex\">".timeunits($timex).'</a>');
}

function timelinks($file) {
	return timelink(3600,$file).' | '.timelink(86400,$file).' | '.timelink(604800,$file).' | '.timelink(2592000,$file);
}