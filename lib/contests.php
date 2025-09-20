<?php

/**
 * Query for currently running contests
 */
function getCurrentContests() {
	return query("SELECT id, title FROM contests WHERE NOW() BETWEEN time_from AND time_to");
}

/**
 * Add a level to a contest if it's currently running and the level isn't already entered
 * @param $cid Contest ID
 * @param $lid Level ID
 */
function addToContest($cid, $lid) {
	$contestEntered = fetch("SELECT title, time_from, time_to FROM contests WHERE id = ?", [$cid]);

	// Check that the contest is actually current
	if (strtotime($contestEntered['time_from']) < time() && time() < strtotime($contestEntered['time_to'])) {
		$entries = result("SELECT COUNT(*) FROM contests_entries WHERE contest = ? AND level = ?", [$cid, $lid]);
		$alreadyEntered = ($entries ? true : false);

		if ($contestEntered && !$alreadyEntered)
			insertInto('contests_entries', ['contest' => $cid, 'level' => $lid]);
	}

	if ($alreadyEntered)
		return 2;

	if ($contestEntered)
		return 1;

	return 0;
}
