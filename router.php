<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

$internal = (isset($path[1]) && (in_array($path[1], ['apZodIaL1', 'principia-version-code', 'upload.php'])));

require('lib/common.php');

function notFound() {
	error('404');
}

function rewritePHP() {
	global $uri;

	if (str_contains($uri, '.php')) {
		header("Location: ".str_replace('.php', '', $uri), true, 301);
		die();
	}
}

if ($path[1]) {
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

		if (!isset($path[2]))
			redirect('/wiki/');
		else { // Reboot into wiki subdir
			chdir('wiki/');
			foreach (glob("lib/*.php") as $filename)
				require($filename);

			require('wiki/index.php');
		}
	}
	elseif ($path[1] == 'api') {
		if (!isset($path[2]))
			redirect('/api/');
		elseif ($path[2] == '') {
			header('Content-Type: text/plain');
			readfile('pages/api/README.md');
		} elseif (file_exists('pages/api/'.$path[2].'.php')) {
			require('pages/api/'.$path[2].'.php');
		}
	}
	elseif (isset($markdownPages[$path[1]])) {
		twigloader()->display('_markdown.twig', [
			'pagetitle' => $markdownPages[$path[1]],
			'file' => $path[1].'.md'
		]);
	}
	elseif ($path[1] == 'download')
		twigloader()->display('download.twig');

	elseif (file_exists('pages/'.$path[1].'.php'))
		require('pages/'.$path[1].'.php');

	elseif ($path[1] == 'apZodIaL1') {

		$page = str_replace('.php', '', $path[2]);

		$internalPage = match ($page) {
			'xx'			=> 'login',				# Login
			'x'				=> 'get_level',			# Get level file
			'xxx'			=> 'get_level',			# Open level in sandbox
			'xxxx'			=> 'get_package',		# Get package file
			'xxxxx'			=> 'get_package_level',	# Get package levels
			'xxxxxx'		=> 'get_level',			# Edit level
			'get_feature'	=> 'get_featured',		# Get featured levels
			'bppfoal2_'		=> 'android_register',	# Android register
			'submit_score'	=> 'submit_score',		# Submit score
			default => null
		};

		if ($internalPage)
			require('internal/'.$internalPage.'.php');
	}
	elseif ($path[1] == 'principia-version-code')
		require('internal/principia-version-code.php');
	elseif ($path[1] == 'upload.php')
		require('internal/upload.php');
	elseif ($path[1] == 'levels_with_no_thumbs')
		require('internal/levels_with_no_thumbs.php');
	elseif ($uri == '/image-to-lua')
		redirect('/image-to-lua/');
	elseif ($uri == '/image-to-lua/')
		require('static/image-to-lua/index.html');
	else {
		rewritePHP();

		if (str_starts_with($uri, '/levels/thumbs')) {
			header("Location: ".str_replace('/levels', '', $uri), true, 301);
			die();
		}

		notFound();
	}

} else
	require('pages/index.php');

if (DEBUG) {
	echo "<pre>== Twig perf dump ==\n";
	$dumper = new \Twig\Profiler\Dumper\TextDumper();
	echo $dumper->dump($profile).'</pre>';
}
