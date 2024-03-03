<?php

define('SAFE_HTML', ['is_safe' => ['html']]);

class PrincipiaExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		global $profiler;

		return [
			new \Twig\TwigFunction('level', 'level', SAFE_HTML),
			new \Twig\TwigFunction('userlink', 'userlink', SAFE_HTML),
			new \Twig\TwigFunction('comments', 'comments', SAFE_HTML),
			new \Twig\TwigFunction('pagination', 'pagination', SAFE_HTML),

			new \Twig\TwigFunction('custom_info', 'customInfo', SAFE_HTML),

			new \Twig\TwigFunction('android_webview_version', 'androidWebviewVersion', SAFE_HTML),
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
			}, SAFE_HTML),

			// Markdown function for inline text, sanitized.
			new \Twig\TwigFilter('markdown_inline', function ($text) {
				$markdown = new Parsedown();
				$markdown->setSafeMode(true);
				return $markdown->line($text);
			}, SAFE_HTML),

			// Markdown function for non-inline text. **NOT SANITIZED, DON'T LET IT EVER TOUCH USER INPUT**
			new \Twig\TwigFilter('markdown_unsafe', function ($text) {
				$markdown = new Parsedown();
				return $markdown->text($text);
			}, SAFE_HTML),

			new \Twig\TwigFilter('relative_time', 'relativeTime'),

			new \Twig\TwigFilter('cmt_num_to_type', 'cmtNumToType'),

		];
	}
}
