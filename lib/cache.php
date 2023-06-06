<?php

class Cache {
	public $memcached;

	function __construct($memcachedServers) {
		$this->memcached = new Memcached();
		$this->memcached->addServers($memcachedServers);
	}

	public function hit($key, $uncachedContent, $expire = 0) {
		return $this->hitMem($key, $uncachedContent, false, $expire);
	}

	public function hitHash($fingerprint, $uncachedContent, $expire = 0) {
		$fingerprint = hash("xxh128", var_export($fingerprint, true));
		return $this->hitMem($fingerprint, $uncachedContent, true, $expire);
	}

	private function hitMem($key, $uncachedContent, $expire = 0) {
		$cached = $this->memcached->get($key);
		if ($cached !== false) {
			return $cached;
		} else {
			$content = $uncachedContent();
			$this->memcached->set($key, $content, $expire);
			return $content;
		}
	}

	// Low-level wrapper functions
	public function get($key) {
		return $this->memcached->get($key);
	}

	public function set($key, $value, $expiration = 0) {
		return $this->memcached->set($key, $value, $expiration);
	}

	public function delete($key) {
		return $this->memcached->delete($key);
	}

	/**
	 * Delete a key that is a hashed fingerprint created with Cache::hitMem.
	 */
	public function deleteHash($fingerprint) {
		$hash = hash("xxh128", var_export($fingerprint, true));
		$this->memcached->delete($hash);
	}
}

$cache = new Cache($memcachedServers);
