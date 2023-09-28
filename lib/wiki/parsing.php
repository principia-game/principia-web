<?php

function parsing($text) {
	$markdown = new ParsedownWiki();

	$text = $markdown->text($text);

	$text = preg_replace_callback('@\|\|ARTICLE_NUMBER\|\|@', 'getPageCount', $text);

	$text = str_replace('<table>', '<table class="wikitable">', $text);

	$text = preg_replace_callback('@{{ ([\w\d\.]+)\((.+?)\) }}@si', 'parseFunctions', $text);

	return $text;
}

function parseFunctions($match) {
	if (str_contains($match[1], '.') || !file_exists('templates/wiki/functions/'.$match[1].'.twig'))
		return '<span class="error">Template error: Invalid function name</span>';

	$data = json_decode($match[2], true);

	if (json_last_error_msg() != "No error")
		return '<span class="error">Template error: '.json_last_error_msg().'</span>';

	return twigloader()->render('wiki/functions/'.$match[1].'.twig', [
		'data' => $data
	]);
}
