<?php

if ($log) redirect('./?ld');

$error = [];

$name = $_POST['name'] ?? null;
$mail = $_POST['mail'] ?? null;

if ($name) $name = trim($name);

if (isset($_POST['action'])) {
	$pass = $_POST['pass'] ?? null;
	$pass2 = $_POST['pass2'] ?? null;
	$captchaId = $_POST['uwu'] ?? null;
	$captchaAnswer = $_POST['jupiter'] ?? null;
	$passwordmanager = $_POST['passwordmanager'] ?? null;

	if (!isset(CAPTCHA[$captchaId])) die('hmm did you try to manipulate the CAPTCHA?');

	if (!$name || !$mail || !$pass)
		$error[] = 'Fill in all fields.';

	if (!$pass || strlen($pass) < 10)
		$error[] = 'Password is too short. (Needs to be at least 10 characters)';

	if (strlen($pass) > 64)
		$error[] = 'Maximum length of passwords is 64 characters.';

	if (!$pass2 || $pass != $pass2)
		$error[] = "The passwords don't match.";

	if (result("SELECT COUNT(*) FROM users WHERE LOWER(name) = ?", [strtolower($name)]))
		$error[] = "Username has already been taken.";

	if (!in_array(strtolower($captchaAnswer), CAPTCHA[$captchaId]['answer']))
		$error[] = "Wrong security question answer.";

	if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $name))
		$error[] = "Username contains invalid characters (Only alphanumeric and underscore allowed).";

	if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
		$error[] = "Email isn't valid.";

	if (result("SELECT COUNT(*) FROM users WHERE email = ?", [mailHash($mail)]))
		$error[] = "You have already registered an account.";

	if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ipaddr]))
		$error[] = "You have already registered an account.";

	if (isTor())
		$error[] = "Your IP address is detected as a Tor exit node. Registrations from Tor have been blocked due to abuse, but if you still want to register then send an email to accountrequest@principia-web.se with your username of choice.";

	if (!$passwordmanager)
		$error[] = "Please save your account credentials in a password manager before registering.";

	if ($error == []) {
		$token = register($name, $pass, $mail, $ipaddr);

		setcookie(COOKIE_NAME, $token, 2147483647);

		redirect('/?rd');
	}
}

$chosenCaptcha = array_rand(CAPTCHA);
$currentCaptcha = CAPTCHA[$chosenCaptcha];
$currentCaptcha['id'] = $chosenCaptcha;

twigloader()->display('register.twig', [
	'error' => $error, 'current_captcha' => $currentCaptcha,
	'name' => $name, 'mail' => $mail
]);
