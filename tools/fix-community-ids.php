#!/usr/bin/php
<?php

print("principia-web tools - fix-community-ids.php\n");
print("===========================================\n");
print("Fixes community IDs in uploaded levels.\n");

require('lib/common.php');

$levels = query("SELECT id FROM levels");

while ($level = $levels->fetch()) {
	//echo "Level ID ".$level['id'].' has community ID '.lvledit($level['id'], 'get-community-id').PHP_EOL;
	$oldid = lvledit($level['id'], 'get-community-id');
	lvledit($level['id'], 'set-community-id', $level['id']);
	print($level['id'].":\t ".$oldid." -> ".$level['id'].PHP_EOL);
}
