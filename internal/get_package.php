<?php
chdir('../');
require('lib/common.php');

$pkg = $_GET['i'] ?? null;
$pkgpath = sprintf('data/packages/%d.ppkg', $pkg);

if (!$pkg || !file_exists($pkgpath)) {
	die('404');
}

query("UPDATE packages SET downloads = downloads + 1 WHERE id = ?", [$pkg]);

readfile($pkgpath);
