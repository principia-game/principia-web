<?php
chdir('../');
require('conf/config.php');
require('lib/mysql.php');

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