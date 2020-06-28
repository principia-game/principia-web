<?php

function twigloader($subfolder = '') {
	global $tplCache, $tplNoCache, $userdata, $log;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	// Add principia-web specific extension
	$twig->addExtension(new PrincipiaExtension());

	$twig->addGlobal('userdata', $userdata);
	$twig->addGlobal('log', $log);

	return $twig;
}

function comments($cmnts, $type, $id) {
	$twig = twigloader('components');
	return $twig->render('comment.php', ['cmnts' => $cmnts, 'type' => $type, 'id' => $id]);
}
