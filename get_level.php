<?php
$level = isset($_GET['i']) ? (int)$_GET['i'] : null;
$levelpath = sprintf('levels/%s.plvl', $level);

if (!$level || !file_exists($levelpath)) {
	// Temporarily disabled due to package problems
	//header('HTTP/1.0 404 Not Found');
	die('404');
}

echo readfile($levelpath);