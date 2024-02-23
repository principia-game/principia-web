<?php
$level = $_GET['i'] ?? null;
$levelpath = sprintf('data/packages/levels/%d.plvl', $level);

if (!$level || !file_exists($levelpath)) {
	offerFile('internal/null.plvl', 'not-found');
	die();
}

offerFile($levelpath, $level.'.plvl');
