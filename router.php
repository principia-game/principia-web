<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

$internal = (isset($path[1]) && (in_array($path[1], ['internal', 'principia-version-code'])));
define('IS_ARCHIVE', $_SERVER['HTTP_HOST'] == 'principia-web-archive.uwu' || $_SERVER['HTTP_HOST'] == 'archive.principia-web.se' || (isset($path[1]) && $path[1] != '' && $path[1] == 'archive'));

require('lib/common.php');

if ($_SERVER['HTTP_HOST'] == 'principia-web-archive.uwu' || $_SERVER['HTTP_HOST'] == 'archive.principia-web.se') {
	require('lib/routes/archive.php');
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
	elseif ($path[1] == 'archive') {
		array_shift($path);
		require('lib/routes/archive.php');
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
	elseif (isset($markdownPages[$path[1]]))
		twigloader()->display('_markdown.twig', [
			'pagetitle' => $markdownPages[$path[1]],
			'file' => $path[1].'.md'
		]);

	elseif ($path[1] == 'download')
		twigloader()->display('download.twig');

	elseif (file_exists('pages/'.$path[1].'.php'))
		require('pages/'.$path[1].'.php');

	elseif ($path[1] == 'internal')
		require('lib/routes/internal.php');

	elseif ($path[1] == 'apZodIaL1') {
		header("x-error-message: Please update your version of Principia to continue using principia-web.");

		http_response_code(500);
		die();
	}
	elseif ($path[1] == 'principia-version-code')
		die('999');
	elseif ($path[1] == 'LICENSE')
		renderPlaintext('LICENSE');
	else {
		rewritePHP();

		notFound();
	}
} else
	require('pages/index.php');

if (DEBUG && isset($_GET['debug'])) {
	echo "<pre>== Twig perf dump ==\n";
	$dumper = new \Twig\Profiler\Dumper\TextDumper();
	echo $dumper->dump($twigProfiler).'</pre>';
}
