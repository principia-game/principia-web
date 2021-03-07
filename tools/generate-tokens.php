#!/usr/bin/php
<?php

print("principia-web tools - generate-tokens.php\n");
print("=========================================\n");
print("This script generates tokens for users who have a null token. It is primarily intended for updating an old principia-web installation to the new cookie auth system.\n");

require('lib/common.php');

$users = query("SELECT id FROM users WHERE token IS NULL");

while ($user = $users->fetch()) {
	$token = bin2hex(random_bytes(20));
	query("UPDATE users SET token = ? WHERE id = ?", [$token, $user['id']]);
}
