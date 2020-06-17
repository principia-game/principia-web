<?php
include('lib/common.php');

if (isset($_POST['action'])) {
	$error = '';

	$name = (isset($_POST['name']) ? $_POST['name'] : null);
	$mail = (isset($_POST['mail']) ? $_POST['mail'] : null);
	$pass = (isset($_POST['pass']) ? $_POST['pass'] : null);
	$pass2 = (isset($_POST['pass2']) ? $_POST['pass2'] : null);

	if (!isset($name)) $error .= 'Blank username. ';
	if (!isset($mail)) $error .= 'Blank email. ';
	if (!isset($pass) || strlen($pass) < 6) $error .= 'Password is too short. ';
	if (!isset($pass2) || $pass != $pass2) $error .= "The passwords don't match. ";
	if (result("SELECT COUNT(*) FROM users WHERE name = ?", [$name])) $error .= "Username has already been taken.";

	if ($error == '') {
		query("INSERT INTO users (name, password, email) VALUES (?,?,?)", [$name,password_hash($pass, PASSWORD_DEFAULT), $mail]);

		header('Location: ./?rd');
	}
}

pageheader();

echo $error;

$twig = twigloader();
echo $twig->render('register.php');

pagefooter();