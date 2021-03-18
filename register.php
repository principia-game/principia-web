<?php
include('lib/common.php');

if ($log) redirect('./');

$error = '';

if (isset($_POST['action'])) {
	$name = (isset($_POST['name']) ? $_POST['name'] : null);
	$mail = (isset($_POST['mail']) ? $_POST['mail'] : null);
	$pass = (isset($_POST['pass']) ? $_POST['pass'] : null);
	$pass2 = (isset($_POST['pass2']) ? $_POST['pass2'] : null);
	$captchaId = (isset($_POST['uwu']) ? $_POST['uwu'] : null);
	$captchaAnswer = (isset($_POST['jupiter']) ? $_POST['jupiter'] : null);

	if (!isset($name)) $error .= 'Blank username. ';
	if (!isset($mail)) $error .= 'Blank email. ';
	if (!isset($pass) || strlen($pass) < 6) $error .= 'Password is too short. ';
	if (!isset($pass2) || $pass != $pass2) $error .= "The passwords don't match. ";
	if (result("SELECT COUNT(*) FROM users WHERE name = ?", [$name])) $error .= "Username has already been taken. ";
	if (!in_array($captchaAnswer, $captcha[$captchaId]['answer'])) $error .= "Wrong security question answer. ";
	if (!preg_match('/[a-zA-Z0-9_]+$/', $name)) $error .= "Username contains invalid characters (Only alphanumeric and underscore allowed). ";
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) $error .= "Email isn't valid. ";

	if ($error == '') {
		register($name, $pass, $mail);

		redirect('./?rd');
	}
}

$chosenCaptcha = array_rand($captcha);
$currentCaptcha = $captcha[$chosenCaptcha];
$currentCaptcha['id'] = $chosenCaptcha;

$twig = twigloader();
echo $twig->render('register.twig', ['error' => $error, 'current_captcha' => $currentCaptcha]);