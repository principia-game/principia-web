<?php

/**
 * Bootstraps an Adminer environment off of principia-web,
 * passing along database credentials to Adminer via a plugin.
 */
function adminerBootstrap() {
	global $userdata;

	define('USERNAME', $userdata['name']);

	function adminer_object() {
		class AdminerPlugin extends Adminer {
			function credentials() {
				return [DB_HOST, DB_USER, DB_PASS];
			}

			function login($login, $password) {
				return ($password == ADMINER_KEY);
			}

			function loginForm() {
				foreach ([
						'driver' => 'server', // MySQL is "server" internally
						'server' => 'principia-web',
						'username' => USERNAME
				] as $key => $value) {
					printf('<input type="hidden" name="auth[%s]" value="%s">', $key, $value);
				}
				echo "<table cellspacing='0' class='layout'>";
				echo "<tr><th colspan='2'>Enter key:";
				echo $this->loginFormField('password', '<tr><th>Key<td>', '<input type="password" name="auth[password]" autocomplete="current-password">');
				echo "</table>";
				echo "<p><input type='submit' value='Login'> ";
				echo checkbox("auth[permanent]", 1, 1, 'Permanent login');
			}
		}

		return new AdminerPlugin();
	}

	require('lib/ext/adminer.php');
}
