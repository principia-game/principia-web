<?php
require_once('../headers.php');

// Router for redirecting from the old archive subdomain

$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

const DOMAIN = 'https://principia-web.se';

if (isset($path[1]) && $path[1] != '') {
	if (in_array($path[1], ['latest', 'level', 'popular', 'random', 'search', 'sitemap', 'top', 'user', 'userlist'])) {
		redirectPerma(DOMAIN.'/archive%s', $_SERVER["REQUEST_URI"]);
	} elseif ($path[1] == 'internal' && in_array($path[2], ['get_level', 'derive_level', 'edit_level'])) {
		redirect(DOMAIN.'/', $_SERVER["REQUEST_URI"]);
	} else {
		http_response_code(404);
		die('<title>404 Not Found</title><center><h1>404 Not Found</h1><hr>principia-web</center>');
	}
} else
	redirectPerma(DOMAIN.'/archive/');
