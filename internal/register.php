<?php

if (!internalKey() || isTor()) die('403');

function sendError($msg) {
	header("x-error-message: ".$msg);
	die();
}

$name = trim($_POST['username'] ?? '');

$mail = $_POST['email'] ?? null;
$pass = $_POST['password'] ?? null;

if (!$name)
	sendError("You need to enter a username.");

if (!$mail || !filter_var($mail, FILTER_VALIDATE_EMAIL))
	sendError("This email is invalid.");

if (!$pass || strlen($pass) < 10)
	sendError("Password is too short. (needs to be at least 10 characters)");

if (result("SELECT COUNT(*) FROM users WHERE LOWER(name) = ?", [strtolower($name)]))
	sendError("This username is already taken.");

if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $name))
	sendError("The username contains invalid characters.");

if (result("SELECT COUNT(*) FROM users WHERE email = ?", [mailHash($mail)]))
	sendError("You have already registered an account.");

if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ipaddr]))
	sendError("You have already registered an account.");

if (isTor())
	sendError("Registrations from Tor have been blocked due to abuse.");

// All possible invalid credentials have been checked, it should be successful now.
register($name, $pass, $mail, $ipaddr);

header('x-notify-message: Registered successfully!');
