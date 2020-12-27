<?php
chdir('../');
$thumb = isset($_GET['i']) ? (int)$_GET['i'] : null;
$thumbpath = sprintf('levels/thumbs/%s.jpg', $thumb);

header("Content-Type: image/jpeg");

if (!$thumb || !file_exists($thumbpath)) {
	echo readfile('assets/placeholder.jpg');
	die();
}

echo readfile($thumbpath);