<?php

// Level types

function typeToCat($type) {
	return match ($type) {
		'custom'	=> 1,
		'adventure'	=> 2,
		'puzzle'	=> 3,
		default 	=> null, // Fallback option: none
	};
}

function catToType($cat) {
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

// Level visibility

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
