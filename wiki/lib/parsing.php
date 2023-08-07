<?php

function parsing($text) {
	$markdown = new ParsedownWiki();
	$markdown->setSafeMode(true);

	$text = $markdown->text($text);

	$text = preg_replace_callback('@{{ ([\w\d\.]+)\((.+?)\) }}@si', 'parseFunctions', $text);

	// Rewrite imgur image links to point to archive at /wiki/images/imgur/
	$text = str_replace('https://i.imgur.com/', '/wiki/images/imgur/', $text);

	return $text;
}

function parseFunctions($match) {
	if (str_contains($match[1], '.') || !file_exists('scripts/'.$match[1].'.lua'))
		return '<span class="error">Template error: Invalid function name</span>';

	$cmd = sprintf(
		"luajit lib/lua/bootstrap.lua %s %s 2>&1",
	escapeshellarg($match[1]), escapeshellarg($match[2]));

	exec($cmd, $output);
	return implode('', $output);
}
