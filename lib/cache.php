<?php

class Cache {
	private $enabled = false;
	private $memcached;

	function __construct($memcachedServers) {
		if (!empty($memcachedServers)) {
			$this->enabled = true;
			$this->memcached = new Memcached();
			foreach ($memcachedServers as $memcachedServer) {
				$this->memcached->addServer($memcachedServer, 11211);
			}
		}
	}

	public function hit($fingerprint, $uncachedContent) {
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
}

$cache = new Cache($memcachedServers);
