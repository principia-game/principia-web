<?php

/**
 * Get the leaderboard for a level
 * @param mixed $lid
 */
function getLeaderboard($lid) {
	return query("SELECT l.*, @userfields
		FROM leaderboard l JOIN users u ON l.user = u.id WHERE l.level = ?
		ORDER BY l.score DESC LIMIT 8",
	[$lid]);
}
