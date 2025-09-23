<?php

function securityHeaders() {
	header("Content-Security-Policy:"
		."default-src 'self';"
		."script-src 'self' 'unsafe-inline';"
		."img-src 'self' data: *.principia-web.se principia-web.se *.voxelmanip.se voxelmanip.se *.imgur.com imgur.com *.github.com github.com *.githubusercontent.com *.postimg.cc postimg.cc;"
		."media-src 'self' *.voxelmanip.se voxelmanip.se;"
		."frame-src *.youtube-nocookie.com itch.io;"
		."style-src 'self' 'unsafe-inline';");

	header("Referrer-Policy: strict-origin-when-cross-origin");
	header("X-Content-Type-Options: nosniff");
	header("X-Frame-Options: SAMEORIGIN");
	header("X-Xss-Protection: 1; mode=block");
}

function redirect($url, ...$args) {
	header('Location: '.sprintf($url, ...$args));
	die();
}

function redirectPerma($url, ...$args) {
	header('Location: '.sprintf($url, ...$args), true, 301);
	die();
}

/**
 * Offers a binary file for download to the browser
 * @param mixed $filepath Path of file
 * @param mixed $savename Default name to save it as
 */
function offerFile($filepath, $savename) {
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"$savename\"");
	header("Content-Length: ".filesize($filepath));

	readfile($filepath);
	die();
}

function sendUserHeaders($userid, $username, $notificationCount) {
	header("x-principia-user-id: ".$userid);
	header("x-principia-user-name: ".$username);
	header("x-principia-unread: ".$notificationCount);
}
