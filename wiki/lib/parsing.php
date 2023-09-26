<?php

function parsing($text) {
	$markdown = new ParsedownWiki();

	$text = $markdown->text($text);

	$text = preg_replace_callback('@\|\|ARTICLE_NUMBER\|\|@', 'getPageCount', $text);

	$text = str_replace('<table>', '<table class="wikitable">', $text);

	$text = preg_replace_callback('@{{ ([\w\d\.]+)\((.+?)\) }}@si', 'parseFunctions', $text);

	// Rewrite imgur image links to point to archive at /wiki/images/imgur/
	$text = str_replace('https://i.imgur.com/', '/wiki/images/imgur/', $text);

	return $text;
}

function parseFunctions($match) {
	if (str_contains($match[1], '.') || !file_exists('templates/functions/'.$match[1].'.twig'))
		return '<span class="error">Template error: Invalid function name</span>';

	// JSON is the most anal language ever
	// (I mean, I'm into anal, but not this kind)
	$match[2] = str_replace(",\n}", "\n}", $match[2]);

	$data = json_decode($match[2], true);

	if (json_last_error_msg() != "No error")
		return '<span class="error">Template error: '.json_last_error_msg().'</span>';

	return _twigloader()->render('functions/'.$match[1].'.twig', [
		'data' => $data
	]);
}
