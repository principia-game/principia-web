<?php

function hitCache($fingerprint, $uncachedContent) {
	$hash = sha1(var_export($fingerprint, true));
	echo var_export($fingerprint, true);
	$cached = result("SELECT content FROM cache WHERE hash = ?", [$hash]);
	if ($cached) {
		return $cached;
	} else {
		$content = preg_replace('/\t|(?:\r?\n[ \t]*)+/s', '', $uncachedContent());
		query("INSERT INTO cache (hash, content) VALUES (?,?)", [$hash, $content]);
		return $content;
	}
}