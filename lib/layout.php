<?php

function twigloader($subfolder = '') {
	global $tplCache, $tplNoCache, $userdata, $log, $basepath, $lpp;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	// Add principia-web specific extension
	$twig->addExtension(new PrincipiaExtension());

	$twig->addGlobal('userdata', $userdata);
	$twig->addGlobal('log', $log);
	$twig->addGlobal('basepath', $basepath);
	$twig->addGlobal('glob_lpp', $lpp);

	return $twig;
}

function comments($cmnts, $type, $id) {
	$twig = twigloader('components');
	return $twig->render('comment.twig', ['cmnts' => $cmnts, 'type' => $type, 'id' => $id]);
}

function pagination($levels, $lpp, $url, $current) {
	$twig = twigloader('components');
	return $twig->render('pagination.twig', ['levels' => $levels, 'lpp' => $lpp, 'url' => $url, 'current' => $current]);
}

function error($title, $message) {
	$twig = twigloader();
	echo $twig->render('_error.twig', ['err_title' => $title, 'err_message' => $message]);
	die();
}

function redirect($url) {
	header(sprintf('Location: %s', $url));
	die();
}

/**
 * Is the useragent Principia's android webview useragent?
 */
function isAndroidWebview() {
	return strpos($_SERVER['HTTP_USER_AGENT'], 'Principia WebView') !== false;
}