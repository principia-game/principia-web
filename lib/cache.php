<?php

class Cache {
	public $enabled = false;
	public $memcached;

	function __construct($memcachedServers) {
		if (!empty($memcachedServers)) {
			$this->enabled = true;
			$this->memcached = new Memcached();
			$this->memcached->addServers($memcachedServers);
		}
	}

	public function hit($key, $uncachedContent, $expire = 0) {
		if ($this->enabled) {
			return $this->hitMem($key, $uncachedContent, false, $expire);
		} else {
			return $uncachedContent();
		}
	}

	public function hitHash($fingerprint, $uncachedContent, $expire = 0) {
		if ($this->enabled) {
			$fingerprint = hash("xxh128", var_export($fingerprint, true));
			return $this->hitMem($fingerprint, $uncachedContent, true, $expire);
		} else {
			return $uncachedContent();
		}
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
		if ($this->enabled) {
			return $this->memcached->get($key);
		}
	}

	public function set($key, $value, $expiration = 0) {
		if ($this->enabled) {
			return $this->memcached->set($key, $value, $expiration);
		}
	}

	public function delete($key) {
		if ($this->enabled) {
			return $this->memcached->delete($key);
		}
	}

	/**
	 * Delete a key that is a hashed fingerprint created with Cache::hitMem.
	 */
	public function deleteHash($fingerprint) {
		if ($this->enabled) {
			$hash = hash("xxh128", var_export($fingerprint, true));
			$this->memcached->delete($hash);
		}
	}
}

$cache = new Cache($memcachedServers);
