<?php

/**
 * Cache trait, contains all caching-system-agnostic code.
 *
 * Classes using this trait need to implement the following methods:
 * - get($key)
 * - set($key, $value, $expiration = 0)
 * - delete($key)
 */
trait Cache {
	public function hit($key, $uncachedContent, $expire = 0) {
		return $this->hitMem($key, $uncachedContent, $expire);
	}

	public function hitHash($fingerprint, $uncachedContent, $expire = 0) {
		$fingerprint = hash("xxh128", var_export($fingerprint, true));
		return $this->hitMem($fingerprint, $uncachedContent, $expire);
	}

	private function hitMem($key, $uncachedContent, $expire = 0) {
		$cached = $this->get($key);
		if ($cached !== false) {
			return $cached;
		} else {
			$content = $uncachedContent();
			$this->set($key, $content, $expire);
			return $content;
		}
	}

	public function deleteHash($fingerprint) {
		$hash = hash("xxh128", var_export($fingerprint, true));
		$this->delete($hash);
	}
}

/**
 * Implements a caching class using memcached.
 */
class CacheMemcached {
	use Cache;

	public $memcached;

	function __construct($memcachedServers) {
		$this->memcached = new Memcached();
		$this->memcached->addServers($memcachedServers);
	}

	public function get($key) {
		return $this->memcached->get($key);
	}

	public function set($key, $value, $expiration = 0) {
		return $this->memcached->set($key, $value, $expiration);
	}

	public function delete($key) {
		return $this->memcached->delete($key);
	}
}

/**
 * Implements a caching class using APCu.
 */
class CacheAPCu {
	use Cache;

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

// Check that APCu is actually enabled.
assert(apcu_enabled());

$cache = new CacheAPCu();

//require('lib/benchmark/cache.php');
