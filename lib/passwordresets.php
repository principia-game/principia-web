<?php

function generatePasswordReset($userid) {
	$tok = bin2hex(random_bytes(32));
	insertInto('passwordresets', ['id' => $tok, 'user' => $userid, 'time' => time()]);

	return $tok;
}
