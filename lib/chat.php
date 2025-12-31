<?php

/**
 * Fetch chat messages from the database.
 * If $lastId is 0, fetch the latest 50 messages
 * Otherwise, fetch messages with ID greater than $lastId
 */
function getMessages($lastId = 0) {
	if ($lastId <= 0) {
		$stmt = query("SELECT m.*, @userfields
				FROM chat_messages m
				JOIN users u ON m.user = u.id
				ORDER BY m.id DESC
				LIMIT 50",
			[]);
		$rows = $stmt->fetchAll();

		if (!$rows)
			$rows = [];

		return array_reverse($rows);
	} else {
		$stmt = query("SELECT m.*, @userfields
				FROM chat_messages m
				JOIN users u ON m.user = u.id
				WHERE m.id > ?
				ORDER BY m.id ASC
				LIMIT 50",
			[$lastId]);
		$rows = $stmt->fetchAll();
		return $rows;
	}
}
