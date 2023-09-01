<?php
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = explode('/', $uri);

$internal = (isset($path[1]) && (in_array($path[1], ['apZodIaL1', 'principia-version-code', 'upload.php'])));

require('lib/common.php');

function notFound() {
	error('404', "The requested page wasn't found.");
}

if ($path[1]) {
	if ($path[1] == 'forum') {
		require('lib/forum/common.php');

		if (!isset($path[2]))
			redirect('/forum/');
		else if ($path[2] == '')
			require('pages/forum/index.php');
		else if (file_exists('pages/forum/'.$path[2].'.php'))
			require('pages/forum/'.$path[2].'.php');
		else
			notFound();
	}
	else if ($path[1] == 'wiki') {
		if (!isset($path[2]))
			redirect('/wiki/');
		else { // Reboot into wiki subdir
			chdir('wiki/');
			require('wiki/index.php');
		}
	}
	else if ($path[1] == 'api') {
		if (!isset($path[2]))
			redirect('/api/');
		else if ($path[2] == '') {
			header('Content-Type: text/plain');
			readfile('pages/api/README.md');
		} else {
			require('api/'.$path[2].'.php');
		}
	}
	else if (isset($markdownPages[$path[1]])) {
		echo twigloader()->render('_markdown.twig', [
			'pagetitle' => $markdownPages[$path[1]],
			'file' => $path[1].'.md'
		]);
	}
	else if ($path[1] == 'download')
		echo twigloader()->render('download.twig');

	else if (file_exists('pages/'.$path[1].'.php'))
		require('pages/'.$path[1].'.php');

	else if ($path[1] == 'apZodIaL1') {

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
	else if ($path[1] == 'principia-version-code')
		require('internal/principia-version-code.php');
	else if ($path[1] == 'upload.php')
		require('internal/upload.php');
	else if ($path[1] == 'levels_with_no_thumbs')
		require('internal/levels_with_no_thumbs.php');
	else
		notFound();
} else
	require('pages/index.php');
