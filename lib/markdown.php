<?php

/**
 * Markdown function for non-inline text, sanitized.
 */
function markdown($text) {
	$markdown = new Parsedown();
	$markdown->setSafeMode(true);
	return $markdown->text($text);
}

/**
 * Markdown function for non-inline text, sanitized and preserving new lines.
 */
function markdownNl($text) {
	$markdown = new Parsedown();
	$markdown->setSafeMode(true);
	$markdown->setBreaksEnabled(true);
	return $markdown->text($text);
}

/**
 * Markdown function for inline text, sanitized.
 */
function markdownInline($text) {
	$markdown = new Parsedown();
	$markdown->setSafeMode(true);
	$markdown->setBreaksEnabled(true);
	return $markdown->line($text);
}

/**
 * Markdown function for non-inline text.
 * **NOT SANITIZED, DON'T LET IT EVER TOUCH USER INPUT**
 */
function markdownUnsafe($text) {
	$markdown = new Parsedown();
	return $markdown->text($text);
}
