<?php

class PrincipiaExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('level', 'level', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', 'userlink', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('uri', 'uri', ['is_safe' => ['html']]),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('cat_to_type', 'cat_to_type'),
		];
	}
}
