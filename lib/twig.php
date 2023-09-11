<?php

class PrincipiaExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		global $profiler;

		return [
			new \Twig\TwigFunction('level', 'level', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('userlink', 'userlink', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('comments', 'comments', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('pagination', 'pagination', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('custom_info', 'customInfo', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('is_android_webview', 'isAndroidWebview', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('profiler_stats', function () use ($profiler) {
				$profiler->getStats();
			}),
			new \Twig\TwigFunction('custom_header', 'customHeader'),
		];
	}
	public function getFilters() {
		return [
			new \Twig\TwigFilter('cat_to_type', 'cat_to_type'),

			new \Twig\TwigFilter('vis_id_to_name', 'visIdToName'),
			new \Twig\TwigFilter('vis_id_to_colour', 'visIdToColour'),

			new \Twig\TwigFilter('cmt_num_to_type', 'cmtNumToType'),

			// Markdown function for non-inline text, sanitized.
			new \Twig\TwigFilter('markdown', function ($text) {
				$markdown = new Parsedown();
				$markdown->setSafeMode(true);
				return $markdown->text($text);
			}, ['is_safe' => ['html']]),

			// Markdown function for inline text, sanitized.
			new \Twig\TwigFilter('markdown_inline', function ($text) {
				$markdown = new Parsedown();
				$markdown->setSafeMode(true);
				return $markdown->line($text);
			}, ['is_safe' => ['html']]),

			// Markdown function for non-inline text. **NOT SANITIZED, DON'T LET IT EVER TOUCH USER INPUT**
			new \Twig\TwigFilter('markdown_unsafe', function ($text) {
				$markdown = new Parsedown();
				return $markdown->text($text);
			}, ['is_safe' => ['html']]),

			new \Twig\TwigFilter('relative_time', 'relativeTime'),

			new \Twig\TwigFilter('cmt_num_to_type', 'cmtNumToType'),

		];
	}
}
