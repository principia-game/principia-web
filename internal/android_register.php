<?php
chdir('../');
require('lib/common.php');

$name = trim($_POST['username'] ?? null);

// Normal behaviour for the open source version.
$mail = $_POST['email'] ?? null;
$pass = $_POST['password'] ?? null;

if (!isset($name))
	die('111'); // 111 is a generic "something went wrong!" code.

if (!isset($mail) || !filter_var($mail, FILTER_VALIDATE_EMAIL))
	die('115'); // "This email is invalid."

if (!isset($pass) || strlen($pass) < 6)
	die('114'); // "The password is invalid."

if (result("SELECT COUNT(*) FROM users WHERE LOWER(name) = ?", [strtolower($name)]))
	die('112'); // "This username is already taken."

if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $name))
	die('113'); // "The username contains invalid characters."

if (result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail]))
	die('116'); // "This email is already in use."

if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ipaddr]))
	die('111'); // Just give the generic "something went wrong!" code.

// All possible invalid credentials have been checked, it should be successful now.
register($name, $pass, $mail);
die('110');
