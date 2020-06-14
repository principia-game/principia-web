<?php
chdir('../');
$pkg = isset($_GET['i']) ? (int)$_GET['i'] : null;
$pkgpath = sprintf('packages/%s.ppkg', $pkg);

if (!$pkg || !file_exists($pkgpath)) {
	header('HTTP/1.0 404 Not Found');
	die('404');
}

echo readfile($pkgpath);