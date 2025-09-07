<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

$internal = (isset($path[1]) && (in_array($path[1], ['internal', 'principia-version-code'])));
define('IS_ARCHIVE', $_SERVER['HTTP_HOST'] == 'principia-web-archive.uwu' || $_SERVER['HTTP_HOST'] == 'archive.principia-web.se');

require('lib/common.php');

if (IS_ARCHIVE) {
	require('lib/archive/router.php');
	return;
}

function notFound() {
	error('404');
}

function rewritePHP() {
	if (str_contains($_SERVER["REQUEST_URI"], '.php'))
		redirectPerma('%s', str_replace('.php', '', $_SERVER["REQUEST_URI"]));
}

if (androidWebviewVersion() && androidWebviewVersion() <= 36)
	die("Please update your Principia. See https://principia-web.se/download in your web browser.");

if (isset($path[1]) && $path[1] != '') {
	if ($path[1] == 'adminer') {
		if (IS_ROOT)
			adminerBootstrap();
		else
			notFound();
	}
	elseif ($path[1] == 'forum') {
		$submodule = 'forum';
		foreach (glob("lib/forum/*.php") as $filename)
			require($filename);

		if (!isset($path[2]))
			redirect('/forum/');
		elseif ($path[2] == '')
			require('pages/forum/index.php');
		elseif (file_exists('pages/forum/'.$path[2].'.php'))
			require('pages/forum/'.$path[2].'.php');
		else {
			rewritePHP();

			notFound();
		}
	}
	elseif ($path[1] == 'wiki') {
		$submodule = 'wiki';
		foreach (glob("lib/wiki/*.php") as $filename)
			require($filename);

		if (!isset($path[2]))
			redirect('/wiki/');
		else // Load Wiki router
			require('pages/wiki/index.php');
	}
	elseif ($path[1] == 'api') {
		if (!isset($path[2]))
			redirect('/api/');
		elseif ($path[2] == '')
			renderPlaintext('pages/api/README.md');
		elseif (file_exists('pages/api/'.$path[2].'.php'))
			require('pages/api/'.$path[2].'.php');
	}
	elseif (isset($markdownPages[$path[1]]))
		twigloader()->display('_markdown.twig', [
			'pagetitle' => $markdownPages[$path[1]],
			'file' => $path[1].'.md'
		]);

	elseif ($path[1] == 'download')
		twigloader()->display('download.twig');

	elseif (file_exists('pages/'.$path[1].'.php'))
		require('pages/'.$path[1].'.php');

	elseif ($path[1] == 'internal') {
		if (file_exists('internal/'.$path[2].'.php'))
			require('internal/'.$path[2].'.php');
		elseif ($path[2] == 'derive_level')
			require('internal/get_level.php');
		elseif ($path[2] == 'edit_level')
			require('internal/get_level.php');
		else
			notFound();
	}
	elseif ($path[1] == 'apZodIaL1') {
		header("x-error-message: Please update your version of Principia to continue using principia-web.");

		http_response_code(500);
		die();
	}
	elseif ($path[1] == 'principia-version-code')
		require('internal/version_code.php');
	elseif ($uri == '/image-to-lua')
		redirect('/image-to-lua/');
	elseif ($uri == '/image-to-lua/')
		require('static/image-to-lua/index.html');
	elseif ($path[1] == 'LICENSE')
		renderPlaintext('LICENSE');
	else {
		rewritePHP();

		notFound();
	}
} else
	require('pages/index.php');

if (DEBUG && false) {
	echo "<pre>== Twig perf dump ==\n";
	$dumper = new \Twig\Profiler\Dumper\TextDumper();
	echo $dumper->dump($profile).'</pre>';
}
