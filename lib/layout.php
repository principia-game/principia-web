<?php

function twigloader() {
	global $tplCache, $tplNoCache;

	$doCache = ($tplNoCache ? false : $tplCache);

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader, [
		'cache' => $doCache,
	]);
	// Add principia-web specific extension
	$twig->addExtension(new PrincipiaExtension());

	return $twig;
}

