<?php

function _twigloader($subfolder = '') {
	$twig = twigloader($subfolder, function () use ($subfolder) {
		return new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	}, function ($loader, $doCache) {

		return new \Twig\Environment($loader, [
			'cache' => ($doCache ? "../".$doCache : $doCache),
		]);
	});

	$twig->addGlobal('wiki', true);

	$twig->addExtension(new PrincipiaWikiExtension());

	return $twig;
}

class PrincipiaWikiExtension extends \Twig\Extension\AbstractExtension {
	public function getFilters() {
		return [
			// Markdown function for wiki, sanitized and using the ToC extension.
			new \Twig\TwigFilter('markdown_wiki', 'parsing', ['is_safe' => ['html']]),
		];
	}
}
