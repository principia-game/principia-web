<?php

const PASTE_VISIBILITY_PUBLIC = 0;
const PASTE_VISIBILITY_UNLISTED = 1;
const PASTE_VISIBILITY_PRIVATE = 2;

function userCanUploadPastes($user) {
	return $user['rank'] > 0 // not banned
		&& getUserLevelCount($user['id']) > 1 // 1 uploaded level
		&& $user['joined'] < time() - 7*24*3600; // 1 week
}

function getPasteById($pid) {
	return fetch("SELECT p.*, @userfields
			FROM pastes p JOIN users u ON p.user = u.id
			WHERE p.pasteid = ?",
		[$pid]);
}

function getPastes($page = 1, $limit = 20) {
	return query("SELECT p.*, @userfields
			FROM pastes p JOIN users u ON p.user = u.id
			ORDER BY p.id DESC"
			.paginate($page, $limit));
}
