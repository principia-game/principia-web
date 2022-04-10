<?php
chdir('../');
require('lib/common.php');

if (!isset($_POST['signature']) || !isset($_POST['userdata'])) die('nah');

$name = $_POST['username'] ?? null;
// Yes... These variables are mixed up. This is intentional because Emil, in his infinite wisdom,
//		  thought that putting the password in 'email' and email in 'password' was a good idea.
$mail = $_POST['password'] ?? null;
$pass = $_POST['email'] ?? null;

// TODO: code 117 - This email has been banned.
//       code 118 is useless to us as we're not checking your license

if (!isset($name)) die('111'); // 111 is a generic "something went wrong!" code.
if (!isset($mail) || !filter_var($mail, FILTER_VALIDATE_EMAIL)) die('115'); // "This email is invalid."
if (!isset($pass) || strlen($pass) < 6) die('114'); // "The password is invalid."
if (result("SELECT COUNT(*) FROM users WHERE name = ?", [$name])) die('112'); // "This username is already taken."
if (!preg_match('/[a-zA-Z0-9_]+$/', $name)) die('113'); // "The username contains invalid characters."
if (result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail])) die('116'); // "This email is already in use."
if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$_SERVER['REMOTE_ADDR']])) die('111'); // Just give the generic "something went wrong!" code.

// All possible invalid credentials have been checked, it should be successful now.
register($name, $pass, $mail);
die('110');
