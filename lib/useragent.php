<?php

/**
 * Extract the platform from a user agent string.
 * This is supposed to be used for getting the platform a level was uploaded from.
 *
 * @param string $ua User agent
 */
function extractPlatform($ua) {
	preg_match('/Principia\/[\d\.]+ \(([\w\s]+)\)/', $ua, $matches);
	return $matches[1] ?? 'N/A';
}

/**
 * Extract the Principia version from a user agent string.
 *
 * @param string $ua User agent
 */
function extractPrincipiaVersion($ua) {
	preg_match('/Principia\/([\d\.]+)/', $ua, $matches);
	return $matches[1] ?? 'N/A';
}

/**
 * If the user agent is an Android WebView, extract its version.
 */
function androidWebviewVersion() {
	global $useragent;
	preg_match('/Principia WebView\/([0-9]+) \(Android\)/', $useragent, $matches);
	return $matches[1] ?? null;
}
