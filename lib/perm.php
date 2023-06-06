<?php

$ranks = [
	-1 => 'Banned',
	0  => 'Guest',
	1  => 'Normal User',
	2  => 'Moderator',
	3  => 'Administrator',
	4  => 'Root',
];

function powIdToName($id) {
	return match ($id) {
		-1 => 'Banned',
		0  => 'Guest',
		1  => 'Normal User',
		2  => 'Moderator',
		3  => 'Administrator',
		4  => 'Root'
	};
}

function powNameToId($id) {
	return match ($id) {
		'Banned'		=> -1,
		'Guest'			=> 0,
		'Normal User'	=> 1,
		'Moderator'		=> 2,
		'Administrator'	=> 3,
		'Root'			=> 4
	};
}

function needsLogin() {
	global $log;
	if (!$log)
		error('403', "This page requires login.");
}
