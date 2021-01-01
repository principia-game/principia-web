<?php
chdir('../');
include('lib/common.php');

if (!isset($_POST['signature']) || !isset($_POST['userdata'])) die('nah');

$name = (isset($_POST['username']) ? $_POST['username'] : null);
// Yes... These variables are mixed up. This is intentional because Emil, in his infinite wisdom,
//		  thought that putting the password in 'email' and email in 'password' was a good idea.
$mail = (isset($_POST['password']) ? $_POST['password'] : null);
$pass = (isset($_POST['email']) ? $_POST['email'] : null);

// TODO: code 113 - The username contains invalid characters.
// TODO: code 116 - This email is already in use.
// TODO: code 117 - This email has been banned.
//       code 118 is useless to us as we're not checking your license

if (!isset($name)) die('111'); // 111 is a generic "something went wrong!" code.
if (!isset($mail)) die('115'); // "This email is invalid."
if (!isset($pass) || strlen($pass) < 6) die('114'); // "The password is invalid."
if (result("SELECT COUNT(*) FROM users WHERE name = ?", [$name])) die('112'); // "This username is already taken."

// All possible invalid credentials have been checked, it should be successful now.
query("INSERT INTO users (name, password, email) VALUES (?,?,?)", [$name,password_hash($pass, PASSWORD_DEFAULT), $mail]);
die('110');
