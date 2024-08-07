<?php

if ($log) redirect('./?ld');

$error = [];

$name = trim($_POST['name'] ?? null);
$mail = $_POST['mail'] ?? null;

if (isset($_POST['action'])) {
	$pass = $_POST['pass'] ?? null;
	$pass2 = $_POST['pass2'] ?? null;
	$captchaId = isset($_POST['uwu']) ? $_POST['uwu'] : null;
	$captchaAnswer = $_POST['jupiter'] ?? null;

	if (!isset($captcha[$captchaId])) die('hmm did you try to manipulate the CAPTCHA?');

	if (!$name || !$mail || !$pass)
		$error[] = 'Fill in all fields.';

	if (!$pass || strlen($pass) < 15)
		$error[] = 'Password is too short. (Needs to be at least 16 characters)';

	if (!$pass2 || $pass != $pass2)
		$error[] = "The passwords don't match.";

	if (result("SELECT COUNT(*) FROM users WHERE LOWER(name) = ?", [strtolower($name)]))
		$error[] = "Username has already been taken.";

	if (!in_array(strtolower($captchaAnswer), $captcha[$captchaId]['answer']))
		$error[] = "Wrong security question answer.";

	if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $name))
		$error[] = "Username contains invalid characters (Only alphanumeric and underscore allowed).";

	if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
		$error[] = "Email isn't valid.";

	if (result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail]))
		$error[] = "You've already registered an account using this email address.";

	if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ipaddr]))
		$error[] = "Creating multiple accounts (alts) aren't allowed.";

	if (isTor())
		$error[] = "Your IP address is detected as a Tor exit node. Registrations from Tor have been blocked due to abuse, but if you still want to register then send an email to accountrequest@principia-web.se with your username of choice.";

	if ($error == []) {
		$token = register($name, $pass, $mail, $ipaddr);

		setcookie(COOKIE_NAME, $token, 2147483647);

		redirect('/?rd');
	}
}

$chosenCaptcha = array_rand($captcha);
$currentCaptcha = $captcha[$chosenCaptcha];
$currentCaptcha['id'] = $chosenCaptcha;

twigloader()->display('register.twig', [
	'error' => $error, 'current_captcha' => $currentCaptcha,
	'name' => $name, 'mail' => $mail
]);
