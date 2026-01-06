<?php

const UPLOADS_DIR = 'data/uploads';
const UPLOADER_MAX_SIZE = 2*1024*1024;
const UPLOADER_USER_MAX_TOTAL_SIZE = 100*1024*1024;

function userCanUploadFiles($user) {
	return $user['rank'] > 0 // not banned
		&& getUserLevelCount($user['id']) >= 2 // 2 uploaded levels
		&& $user['posts'] >= 1 // 1 forum post
		&& $user['joined'] < time() - 6*30*24*3600; // 6 months
}

function totalUserUploadSize($userId) {
	$files = query("SELECT fileid, filename FROM uploader_files WHERE user = ?", [$userId]);
	$totalSize = 0;
	while ($row = $files->fetch())
		$totalSize += filesize(uploadPath($row['fileid'], $row['filename']));
	return $totalSize;
}

function getUploadedFiles($page = 1, $limit = 20) {
	return query("SELECT f.*, @userfields
			FROM uploader_files f JOIN users u ON u.id = f.user
			ORDER BY date DESC"
		.paginate($page, $limit));
}

function sizeunit($bytes, $precision = 2) {
	$units = ['B', 'kB', 'MB', 'GB', 'TB'];
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= (1 << (10 * $pow));
	return round($bytes, $precision).' '.$units[$pow];
}

function getSize($id, $filename) {
	return sizeunit(filesize(uploadPath($id, $filename)));
}

function uploadPath($id, $filename) {
	return UPLOADS_DIR.'/'.$id.'.'.strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

function uploadUrl($id, $filename) {
	return '/uploads/'.$id.'.'.strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}
