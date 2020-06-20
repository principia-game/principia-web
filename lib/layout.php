<?php

function twigloader() {
	global $tplCache, $tplNoCache, $userdata, $log;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	// Add principia-web specific extension
	$twig->addExtension(new PrincipiaExtension());

	$twig->addGlobal('userdata', $userdata);
	$twig->addGlobal('log', $log);

	return $twig;
}

