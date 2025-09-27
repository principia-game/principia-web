<?php

function twigloaderWiki() {
	$twig = twigloader();

	$twig->addGlobal('submodule', 'wiki');

	$twig->addFilter('markdown_wiki', 'parsing');

	return $twig;
}
