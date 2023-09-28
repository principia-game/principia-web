<?php

function twigloaderWiki() {
	$twig = twigloader();

	$twig->addGlobal('submodule', 'wiki');

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
