<?php
chdir('../');
require('lib/common.php');

$pkg = isset($_GET['i']) ? (int)$_GET['i'] : null;
$pkgpath = sprintf('data/packages/%d.ppkg', $pkg);

if (!$pkg || !file_exists($pkgpath)) {
	header('HTTP/1.0 404 Not Found');
	die('404');
}

query("UPDATE packages SET downloads = downloads + '1' WHERE id = ?", [$pkg]);

readfile($pkgpath);
