<?php
chdir('../');
require('conf/config.php');
require('lib/mysql.php');

// lazy local ip check
if (substr($_SERVER['REMOTE_ADDR'],0,8) != "192.168.") {
	die("you shouldn't be here");
}

header('Content-Type: application/json');

$lastlevel = result("SELECT id FROM levels ORDER BY id DESC LIMIT 1");

$levels = [];
for ($i = 1; $i <= $lastlevel; $i++) {
	if (!file_exists(sprintf('levels/thumbs/%s.jpg', $i))) {
		$levels[] = $i;
	}
}

echo json_encode(['levels_with_no_thumbs' => $levels]);
