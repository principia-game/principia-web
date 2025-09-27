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

function incrementLevelView(&$level) {
	query("UPDATE levels SET views = views + '1' WHERE id = ?", [$level['id']]);
	$level['views']++;
}

function getLevelDerivatives($lid) {
	global $userfields;

	return query("SELECT l.id id,l.title title, $userfields
		FROM levels l JOIN users u ON l.author = u.id WHERE l.parent = ? AND l.visibility = 0
		ORDER BY l.id DESC",
	[$lid]);
}

/**
 * Get data of the parent level, if it has one
 * @param $level Level data
 */
function getParentLevel($level) {
	global $userfields;

	if (!$level['parent']) return null;

	return fetch("SELECT l.id id, l.title title, $userfields
			FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ? AND l.visibility = 0",
		[$level['parent']]);
}

/**
 * Get amount of public levels a user has uploaded
 * @param $uid User ID
 */
function getUserLevelCount($uid) {
	global $cache;

	return $cache->hit((IS_ARCHIVE ? 'archive:' : '').'levelcount_'.$uid, function () use ($uid) {
		return result("SELECT COUNT(*) FROM levels l WHERE l.author = ? AND l.visibility = 0", [$uid]);
	});
}

/**
 * Get a number of random public levels
 * @param $amount Amount of random levels
 */
function randomLevels($amount) {
	global $cache, $userfields;

	$publicLevels = $cache->hit((IS_ARCHIVE ? 'archive:' : '').'public_levels', fn() =>
		fetchArray(query("SELECT id FROM levels WHERE visibility = 0"))
	, IS_ARCHIVE ? 0 : 3600);

	if (count($publicLevels) < $amount)
		return [];

	$randomLevelIds = [];
	while (count($randomLevelIds) < $amount) {
		$randomId = $publicLevels[array_rand($publicLevels)]['id'];

		if (!in_array($randomId, $randomLevelIds))
			$randomLevelIds[] = $randomId;
	}

	$randomLevels = fetchArray(query("SELECT $userfields, l.id, l.title
		FROM levels l JOIN users u ON l.author = u.id
		WHERE l.id IN (".implode(",", $randomLevelIds).")"));

	// Don't make the random levels get ordered by ID
	shuffle($randomLevels);

	return $randomLevels;
}

function latestLevels($cat) {
	global $userfields;

	return query("SELECT l.id,l.title,$userfields
			FROM levels l JOIN users u ON l.author = u.id
			WHERE l.cat = ? AND l.visibility = 0
			ORDER BY l.id DESC LIMIT 8",
		[$cat]);
}

function topLevels() {
	global $userfields;

	return query("SELECT l.id,l.title,$userfields
			FROM levels l JOIN users u ON l.author = u.id
			WHERE l.visibility = 0
			ORDER BY l.likes DESC, l.id DESC LIMIT 8");
}
