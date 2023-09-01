#!/usr/bin/php
<?php
print("Hashes emails in a database with unhashed emails.\n\n");

require('lib/common.php');

$users = query("SELECT id, email FROM users");

while ($user = $users->fetch()) {
	$id = $user['id'];
	$emailhash = mailhash($user['email']);

	query("UPDATE users SET email = ? WHERE id = ?", [$emailhash, $id]);
}
