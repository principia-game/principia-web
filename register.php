<?php
require('lib/common.php');

if ($log) redirect('./');

$error = '';

if (isset($_POST['action'])) {
	$name = trim($_POST['name'] ?? null);
	$mail = $_POST['mail'] ?? null;
	$pass = $_POST['pass'] ?? null;
	$pass2 = $_POST['pass2'] ?? null;
	$captchaId = isset($_POST['uwu']) ? $_POST['uwu'] : null;
	$captchaAnswer = $_POST['jupiter'] ?? null;

	if (!isset($captcha[$captchaId])) ipBan($ipaddr, 'Manipulating CAPTCHA questions?');

	if (!$name)
		$error .= 'Blank username. ';

	if (!$mail)
		$error .= 'Blank email. ';

	if (!$pass || strlen($pass) < 6)
		$error .= 'Password is too short. ';

	if (!$pass2 || $pass != $pass2)
		$error .= "The passwords don't match. ";

	if (result("SELECT COUNT(*) FROM users WHERE LOWER(name) = ?", [strtolower($name)]))
		$error .= "Username has already been taken. ";

	if (!in_array(strtolower($captchaAnswer), $captcha[$captchaId]['answer']))
		$error .= "Wrong security question answer. ";

	if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $name))
		$error .= "Username contains invalid characters (Only alphanumeric and underscore allowed). ";

	if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
		$error .= "Email isn't valid. ";

	if (result("SELECT COUNT(*) FROM users WHERE email = ?", [$mail]))
		$error .= "You've already registered an account using this email address. ";

	if (result("SELECT COUNT(*) FROM users WHERE ip = ?", [$ipaddr]))
		$error .= "Creating multiple accounts (alts) aren't allowed. ";

	if ($error == '') {
		$token = register($name, $pass, $mail);

		setcookie($cookieName, $token, 2147483647);

		redirect('/?rd');
	}
}

$chosenCaptcha = array_rand($captcha);
$currentCaptcha = $captcha[$chosenCaptcha];
$currentCaptcha['id'] = $chosenCaptcha;

echo twigloader()->render('register.twig', ['error' => $error, 'current_captcha' => $currentCaptcha]);