<?php

if (DEBUG)
	$twigProfiler = new \Twig\Profiler\Profile();

class Template {
	private $twig;

	public function __construct() {
		$loader = new \Twig\Loader\FilesystemLoader('templates/');

		$this->twig = new \Twig\Environment($loader, [
			'cache' => TPL_CACHE,
			'auto_reload' => true,
		]);

		$this->addFunctions();
		$this->addFilters();
		$this->addGlobals();

		if (DEBUG)
			$this->twig->addExtension(new \Twig\Extension\ProfilerExtension($GLOBALS['twigProfiler']));
	}

	private function addFunctions() {
		$this->addFunction('level');
		$this->addFunction('package');
		$this->addFunction('userlink');
		$this->addFunction('comments');
		$this->addFunction('pagination');
		$this->addFunction('android_webview_version', 'androidWebviewVersion');
		$this->addFunction('profiler_stats', function () {
			global $profiler;
			$profiler->getStats();
		});
		$this->addFunction('custom_header', 'customHeader');
		$this->addFunction('icon', function ($name) {
			return file_get_contents("templates/icons/{$name}.svg");
		}, ['is_safe' => ['html']]);

		$this->addFunction('archive_prefix', function () {
			return IS_ARCHIVE ? '/archive' : '';
		});
		$this->addFunction('blarg', 'blarg');

		$this->addFunction('get_size', 'getSize');
		$this->addFunction('upload_url', 'uploadUrl');
	}

	private function addFilters() {
		$this->addFilter('cat_to_type', 'catToType');

		$this->addFilter('vis_id_to_name', 'visIdToName');
		$this->addFilter('vis_id_to_colour', 'visIdToColour');

		$this->addFilter('cmt_num_to_type', 'cmtNumToType');

		$this->addFilter('markdown', 'markdown');
		$this->addFilter('markdown_inline', 'markdownInline');
		$this->addFilter('markdown_unsafe', 'markdownUnsafe');

		$this->addFilter('relative_time', 'relativeTime');

		$this->addFilter('ipv6_to_ipv4');

		$this->addFilter('archive_prefix', function ($path) {
			return (IS_ARCHIVE ? '/archive' : '') . $path;
		});
	}

	private function addGlobals() {
		$this->addGlobal('submodule', 'main');
		$this->addGlobal('userdata', $GLOBALS['userdata']);
		$this->addGlobal('log', $GLOBALS['log']);
		$this->addGlobal('glob_lpp', LPP);
		$this->addGlobal('footerlinks', $GLOBALS['footerlinks']);
		$this->addGlobal('domain', DOMAIN);
		$this->addGlobal('uri', $_SERVER['REQUEST_URI'] ?? null);
		if (($GLOBALS['path'][1] ?? '') == 'forum')
			$this->addGlobal('pagename', '/forum/'.($GLOBALS['path'][2] ?? ''));
		else
			$this->addGlobal('pagename', '/'.(IS_ARCHIVE ? 'archive/' : '').($GLOBALS['path'][1] ?? ''));
	}

	public function addFunction($name, $callable = null, $options = null) {
		$this->twig->addFunction(new \Twig\TwigFunction($name, $callable ?? $name, $options ?? ['is_safe' => ['html']]));
	}

	public function addFilter($name, $callable = null, $options = null) {
		$this->twig->addFilter(new \Twig\TwigFilter($name, $callable ?? $name, $options ?? ['is_safe' => ['html']]));
	}

	public function addGlobal($name, $value) {
		$this->twig->addGlobal($name, $value);
	}

	public function render($template, $context = []) {
		return $this->twig->render($template, $context);
	}

	public function display($template, $context = []) {
		$this->twig->display($template, $context);
	}
}

function twigloader() {
	return new Template();
}
