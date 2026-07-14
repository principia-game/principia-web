<?php

$paths = [
	// Compatibility route for internal endpoints on the archive subdomain
	'archive_level' => function() {
		$_GET['i'] += ARCHIVE_LVL_OFFSET;
		require('pages/internal/get_level.php');
	},
	'derive_level' => 'get_level',
	'edit_level' => 'get_level',
	'get_featured' => function() {
		internalAuth();
		offerFile('data/featured/fl.cache', 'fl.cache');
	},
	'get_level' => 'get_level',
	'get_package_level' => 'get_package_level',
	'get_package' => 'get_package',
	'login' => 'login',
	'register' => 'register',
	'submit_score' => 'submit_score',
	'upload' => 'upload',
	'version_code' => function() {
		internalAuth();

		$latestnews = News::getLatestArticle();

		echo LATEST_VERSION_CODE.":Latest news article: ".$latestnews['title'];
	}
];

if (isset($paths[$path[2]])) {
	if ($_SERVER['HTTP_HOST'] === 'principia-web.uwu')
		header("Access-Control-Allow-Origin: https://principia-web.uwu");

	header("Access-Control-Expose-Headers: X-Error-Message, X-Notify-Message, X-Error-Action, X-Principia-User-Id, X-Principia-User-Name, X-Principia-Unread");
	header("Access-Control-Allow-Credentials: true");

	if (!is_string($paths[$path[2]])) {
		$paths[$path[2]]();
	} else
		require('pages/internal/'.$paths[$path[2]].'.php');
} else
	notFound();
