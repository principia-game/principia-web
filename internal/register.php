<?php

$modern = ($uri == "/internal/register");

if ($modern && !internalKey()) die('403');

function getError($code) {
	global $modern;

	$errors = [
		111 => "You have already registered an account.",
		112 => "This username is already taken.",
		113 => "The username contains invalid characters.",
		114 => "Password is too short.",
		115 => "This email is invalid.",
		116 => "This email is already in use."
	];

	if ($modern)
		header("x-error-message: ".$errors[$code]);
	else
		echo $code;

	die();
}

$name = trim($_POST['username'] ?? '');

// Normal behaviour for the open source version.
$mail = $_POST['email'] ?? null;
$pass = $_POST['password'] ?? null;

if (!$name) // XXX
	getError('112'); // 111 is a generic "something went wrong!" code.

if (!$mail || !filter_var($mail, FILTER_VALIDATE_EMAIL))
	getError('115'); // "This email is invalid."

if (!$pass || strlen($pass) < 6)
	getError('114'); // "The password is invalid."

if (result("SELECT COUNT(*) FROM users WHERE LOWER(name) = ?", [strtolower($name)]))
	getError('112'); // "This username is already taken."

if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $name))
	getError('113'); // "The username contains invalid characters."

if (result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail]))
	getError('116'); // "This email is already in use."

if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ipaddr]))
	getError('111'); // Just give the generic "something went wrong!" code.

// All possible invalid credentials have been checked, it should be successful now.
register($name, $pass, $mail, $ipaddr);

if ($modern)
	header('x-notify-message: Registered successfully!');
else
	die('110');
