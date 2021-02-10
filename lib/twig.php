<?php

class PrincipiaExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			new \Twig\TwigFunction('level', 'level', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', 'userlink', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('comments', 'comments', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('pagination', 'pagination', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('custom_info', 'customInfo', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('is_android_webview', 'isAndroidWebview', ['is_safe' => ['html']])
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('cat_to_type', 'cat_to_type'),
			new \Twig\TwigFilter('markdown', function ($text) {
				$markdown = new Parsedown();
				return $markdown->text($text);
			}, ['is_safe' => ['html']])
		];
	}
}
