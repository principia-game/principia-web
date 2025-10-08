<?php

function getComments($type, $id) {
	$typeNum = cmtTypeToNum($type);
	return query("SELECT c.*, @userfields
			FROM comments c JOIN users u ON c.author = u.id WHERE c.type = ? AND c.level = ?
			ORDER BY c.id DESC",
		[$typeNum, $id]);
}

function cmtTypeToNum($type) {
	return match ($type) {
		'level'		=> 1,
		'contest'	=> 3,
		'user'		=> 4,
		'package'	=> 6,
		'archive/level' => 7
	};
}

function cmtNumToType($num) {
	return match ($num) {
		1 => 'level',
		3 => 'contest',
		4 => 'user',
		6 => 'package',
		7 => 'archive/level'
	};
}
