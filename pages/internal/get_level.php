<?php
header("Access-Control-Allow-Origin: *");

$level = $_GET['i'] ?? null;
if ($level > ARCHIVE_LVL_OFFSET) {
	$level -= ARCHIVE_LVL_OFFSET;
	$isArchive = true;
	$levelpath = sprintf('data/archive/levels/%d.plvl', $level);
} else {
	$levelpath = sprintf('data/levels/%d.plvl', $level);
}

if (extractPrincipiaVersion(HTTP_UA) < LATEST_VERSION_CODE) {
	header("x-error-message: Please update your version of Principia to be able to play levels.");
	http_response_code(500);
	die();
}

if (!$level || !file_exists($levelpath))
	offerFile('static/assets/null.plvl', 'not-found');

if (!isset($isArchive))
	query("UPDATE levels SET downloads = downloads + 1 WHERE id = ?", [$level]);

offerFile($levelpath, $level.'.plvl');
