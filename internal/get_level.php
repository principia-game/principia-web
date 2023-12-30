<?php
$level = $_GET['i'] ?? null;
$levelpath = sprintf('data/levels/%d.plvl', $level);

if (!$level || !file_exists($levelpath)) {
	readfile('internal/null.plvl');
	die();
}

query("UPDATE levels SET downloads = downloads + 1 WHERE id = ?", [$level]);

readfile($levelpath);
