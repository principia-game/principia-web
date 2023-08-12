<?php

assert(apcu_enabled());

class Cache {
	public function hit($key, $uncachedContent, $expire = 0) {
		return $this->hitMem($key, $uncachedContent, false, $expire);
	}

	private function hitMem($key, $uncachedContent, $expire = 0) {
		$cached = apcu_fetch($key);
		if ($cached !== false) {
			return $cached;
		} else {
			$content = $uncachedContent();
			apcu_store($key, $content, $expire);
			return $content;
		}
	}

	// Low-level wrapper functions
	public function get($key) {
		return apcu_fetch($key);
	}

	public function set($key, $value, $expiration = 0) {
		return apcu_store($key, $value, $expiration);
	}

	public function delete($key) {
		return apcu_delete($key);
	}
}

$cache = new Cache();
