<?php
chdir('../');
require('conf/config.php');
require('lib/mysql.php');

header('Content-Type: application/json');

$levellist = query("SELECT id FROM levels WHERE visibility = 0");

$levels = [];
while ($level = $levellist->fetch()) {
	$i = $level['id'];
	if (!file_exists(sprintf('levels/thumbs/%s.jpg', $i))) {
		$levels[] = $i;
	}
}

echo json_encode(['levels_with_no_thumbs' => $levels]);
