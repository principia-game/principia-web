<?php
chdir('../');

$level = $_GET['i'] ?? null;
$levelpath = sprintf('data/packages/levels/%d.plvl', $level);

if (!$level || !file_exists($levelpath)) {
	readfile('internal/null.plvl');
	die();
}

readfile($levelpath);
