<?php
include('lib/common.php');

if (isset($_POST['action'])) {
	$logindata = fetch("SELECT id,password FROM users WHERE name = ?", [$_POST['name']]);

	if (password_verify($_POST['pass'], $logindata['password'])) {
		setcookie('user', $logindata['id']);
		setcookie('passenc', base64_encode($_POST['pass']));
	}
	header('Location: ./');
}

pageheader();

?>
<h2>Login</h2>
<form class="reglog" method="POST">
	<p><span>Username:</span> <input type="text" name="name" size="25" maxlength="25"></p>
	<p><span>Password:</span> <input type="password" name="pass" size="25" maxlength="32"></p>
	<a href="register.php">Don't have an account yet? Register!</a>
	<p><input type="submit" class="submit" name="action" value="Login"></p>
</form>
<?php pagefooter(); ?>