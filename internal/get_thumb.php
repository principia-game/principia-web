<?php
chdir('../');
$thumb = isset($_GET['i']) ? (int)$_GET['i'] : null;
$thumbpath = sprintf('levels/thumbs/%s.jpg', $thumb);

if (!$thumb || !file_exists($thumbpath)) {
	echo readfile('assets/placeholder.png');
}

echo readfile($thumbpath);