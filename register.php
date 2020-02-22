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
?>
<h2>Register</h2>
<form class="reglog" method="POST">
	<p><span>Username:</span> <input type="text" name="name" size="25" maxlength="25"></p>
	<p><span>Email:</span> <input type="text" name="mail" size="25"></p>

	<p><span>Password:</span> <input type="password" name="pass" size="25" maxlength="32"></p>
	<p><span>Password again:</span> <input type="password" name="pass2" size="25" maxlength="32"></p>

	<p><input type="submit" class="submit" name="action" value="Register"></p>
</form>
<?php pagefooter() ?>