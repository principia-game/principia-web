<?php

$paths = [
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
	'submit_level' => 'submit_level',
	'upload' => 'upload',
	'version_code' => function() {
		internalAuth();

		$latestnews = News::getLatestArticle();

		echo LATEST_VERSION_CODE.":Latest news article: ".$latestnews['title'];
	}
];

if (isset($paths[$path[2]])) {

	if (!is_string($paths[$path[2]])) {
		$paths[$path[2]]();
	} else
		require('pages/internal/'.$paths[$path[2]].'.php');
} else
	notFound();
