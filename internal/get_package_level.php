<?php
chdir('../');

$level = isset($_GET['i']) ? (int)$_GET['i'] : null;
$levelpath = sprintf('packages/levels/%s.plvl', $level);

if (!$level || !file_exists($levelpath)) {
	// Temporarily disabled due to package problems
	//header('HTTP/1.0 404 Not Found');
	echo readfile('internal/null.plvl');
	die();
}

echo readfile($levelpath);