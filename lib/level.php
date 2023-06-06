<?php

function type_to_cat($type) {
	return match ($type) {
		'custom'	=> 1,
		'adventure'	=> 2,
		'puzzle'	=> 3,
		default 	=> 99, // Fallback option: none
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
		'chat'		=> 5,
		'package'	=> 6
	};
}

function cmtNumToType($num) {
	return match ($num) {
		1 => 'level',
		2 => 'news',
		3 => 'contest',
		4 => 'user',
		5 => 'chat',
		6 => 'package'
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
		throw new Exception('No platform found (input is probably garbled)');
}
