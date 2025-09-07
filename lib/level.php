<?php

function likeLevel($lid, $user) {
	global $cachectrl;

	query("UPDATE levels SET likes = likes + '1' WHERE id = ?", [$lid]);

	insertInto('likes', ['user' => $user, 'level' => $lid]);
	$cachectrl->invIndexTop();
}

function toggleLevelLock($level, $visibility) {
	global $cachectrl;

	$visibility = $visibility == 1 ? 0 : 1;

	query("UPDATE levels SET visibility = ? WHERE id = ?", [$visibility, $level['id']]);

	$cachectrl->invLevelCount($level['author']);
	$cachectrl->invIndex();

	return $visibility;
}

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

function cmtTypeToNum($type) {
	return match ($type) {
		'level'		=> 1,
		'contest'	=> 3,
		'user'		=> 4,
		'package'	=> 6
	};
}

function cmtNumToType($num) {
	return match ($num) {
		1 => 'level',
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
	return $matches[1] ?? 'N/A';
}

function randomLevels($amount) {
	// This is used for the community site archive's random levels only right now
	global $publicLevels, $userfields;

	$levelIds = [];
	for ($i = 0; $i < $amount; $i++)
		$levelIds[] = $publicLevels[array_rand($publicLevels)];

	return query("SELECT $userfields,l.id,l.title FROM levels l JOIN users u ON l.author = u.id WHERE l.id IN (".implode(",", $levelIds).")");
}
