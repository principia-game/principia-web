{% extends "_layout.php" %}

	{% block content %}
<h2>Login</h2>
<form class="reglog" method="POST">
	<p>
		<span>Username:</span>
		<input type="text" name="name" size="25" maxlength="25">
	</p>
	<p>
		<span>Password:</span>
		<input type="password" name="pass" size="25" maxlength="32">
	</p>
	<a href="register.php">Don't have an account yet? Register!</a>
	<p><input type="submit" class="submit" name="action" value="Login"></p>
</form>
	{% endblock %}