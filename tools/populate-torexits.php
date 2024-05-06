<?php // thanks to kimapr for providing me help with code wuality
if (php_sapi_name() !== 'cli') die('whar');
$fh = fopen('/tmp/torbulkexitlist', "w");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://check.torproject.org/torbulkexitlist");
curl_setopt($ch, CURLOPT_FILE, $fh);
curl_exec($ch);
curl_close($ch);
$lst = explode(PHP_EOL, trim(str_replace("\r", '', file_get_contents('/tmp/torbulkexitlist'))));
file_put_contents('data/torexits.json', json_encode($lst));
