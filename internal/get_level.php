<?php
$level = $_GET['i'] ?? null;
$levelpath = sprintf('data/levels/%d.plvl', $level);

if (!$level || !file_exists($levelpath)) {
	offerFile('internal/null.plvl', 'not-found');
	die();
}

query("UPDATE levels SET downloads = downloads + 1 WHERE id = ?", [$level]);

offerFile($levelpath, $level.'.plvl');
