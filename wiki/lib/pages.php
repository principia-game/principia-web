<?php

function checkPageExistance($pagename) {
	global $cache;

	return $cache->hit('wpe_'.base64_encode($pagename), function () use ($pagename) {
		return result("SELECT COUNT(*) FROM wikipages WHERE BINARY title = ?", [$pagename]);
	}) == 1;
}

/**
 * Check the page name for various things that probably should not be in it.
 */
function legalPageName($page) {
	return !(str_starts_with($page, '..')
		|| str_contains($page, '.php'));
}
