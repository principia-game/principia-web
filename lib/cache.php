<?php

class Cache {
	private $enabled = false;
	public $memcached;

	function __construct($memcachedServers) {
		if (!empty($memcachedServers)) {
			$this->enabled = true;
			$this->memcached = new Memcached();
			foreach ($memcachedServers as $memcachedServer) {
				$this->memcached->addServer($memcachedServer, 11211);
			}
		}
	}

	public function hit($fingerprint, $uncachedContent, $expire = 0) {
		if ($this->enabled) {
			return $this->hitMem($fingerprint, $uncachedContent);
		} else {
			return $uncachedContent();
		}
	}

	private function hitMem($fingerprint, $uncachedContent) {
		// TODO: Switch to xxHash when PHP 8.1 releases.
		$hash = sha1(var_export($fingerprint, true));
		$cached = $this->memcached->get($hash);
		if ($cached) {
			return $cached;
		} else {
			$content = $uncachedContent();
			$this->memcached->set($hash, $content);
			return $content;
		}
	}

	// Low-level wrapper functions
	public function get($key) {
		if ($this->enabled) {
			$this->memcached->get($key);
		}
	}

	public function set($key, $value, $expiration = 0) {
		if ($this->enabled) {
			$this->memcached->set($key, $value, $expiration);
		}
	}

	public function delete($key) {
		if ($this->enabled) {
			$this->memcached->delete($key);
		}
	}
}

$cache = new Cache($memcachedServers);
