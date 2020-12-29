<?php
chdir('../');
require('conf/config.php');
require('lib/mysql.php');

// lazy local ip check
if (substr($_SERVER['REMOTE_ADDR'],0,8) != "192.168.") {
	die("you shouldn't be here");
}

header('Content-Type: application/json');

echo '{ "levels_with_no_thumbs": [';

$levelcount = result("SELECT COUNT(*) FROM levels");

for ($i = 1; $i <= $levelcount; $i++)
	if (!file_exists(sprintf('levels/thumbs/%s.jpg', $i)))
		$levels[] = $i;

$out = '';
foreach ($levels as $level) {
	if ($out) $out .= ',';
	$out .= $level;
}
echo $out;

echo '] }';