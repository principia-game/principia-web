<?php
$level = $_GET['i'] ?? null;
if (IS_ARCHIVE)
	$levelpath = sprintf('data/archive/levels/%d.plvl', $level);
else
	$levelpath = sprintf('data/levels/%d.plvl', $level);

if (!$level || !file_exists($levelpath))
	offerFile('static/assets/null.plvl', 'not-found');

if (!IS_ARCHIVE)
	query("UPDATE levels SET downloads = downloads + 1 WHERE id = ?", [$level]);

offerFile($levelpath, $level.'.plvl');
