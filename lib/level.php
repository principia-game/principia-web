<?php

function type_to_cat($type) {
	return match ($type) {
		'custom'	=> 1,
		'adventure'	=> 2,
		'puzzle'	=> 3,
		default 	=> null, // Fallback option: none
	};
}

function cat_to_type($cat) {
	return match ($cat) {
		1 => 'custom',
		2 => 'adventure',
		3 => 'puzzle'
	};
}

function catConvert($cat) {
	return match ($cat) {
		0 => 3,
		1 => 2,
		2 => 1,
	};
}

function cmtTypeToNum($type) {
	return match ($type) {
		'level'		=> 1,
		'news'		=> 2,
		'contest'	=> 3,
		'user'		=> 4,
		'package'	=> 6
	};
}

function cmtNumToType($num) {
	return match ($num) {
		1 => 'level',
		2 => 'news',
		3 => 'contest',
		4 => 'user',
		6 => 'package'
	};
}

function visIdToName($id) {
	return match ($id) {
		0 => 'Public',
		1 => 'Locked',
		2 => 'Unlisted',
		default => 'N/A'
	};
}

function visIdToColour($id) {
	return match ($id) {
		0 => 'bg-green',
		1 => 'bg-yellow',
		2 => 'bg-cyan',
		default => ''
	};
}

/**
 * Extract the platform from a user agent string.
 * This is supposed to be used for getting the platform a level was uploaded from.
 *
 * @param string $ua User agent
 * @return string Platform.
 */
function extractPlatform($ua) {
	preg_match('/\((\w+)\)/', $ua, $matches);
	if (isset($matches[1]))
		return $matches[1];
	else
		return 'N/A';
}
