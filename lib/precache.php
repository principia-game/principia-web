<?php

/**
 * Class that precaches data in memcached ahead of time.
 */
class PreCache {
	private $cache;
	private $preCacheLevel;
	private $updated = false;

	function __construct($cacheObj) {
		$this->cache = $cacheObj;
		$this->preCacheLevel = $this->cache->get('precache_level');

		// Fallback to lowest precache support level if memcached was just initialised.
		if ($this->preCacheLevel === false) $this->preCacheLevel = 0;

		$this->runPreCache();

		if ($this->updated) $this->cache->set('precache_level', $this->preCacheLevel);
	}

	/**
	 * Run all potential precache actions.
	 */
	private function runPreCache() {
		// Cache #1: IP bans
		$this->preCacheAction(1, function ($cache) {
			$ipbans = query("SELECT * FROM ipbans");

			while ($ipban = $ipbans->fetch()) {
				$cache->set('ipb_'.$ipban['ip'], $ipban['reason']);
			}
		});


	}

	/**
	 * Run a precache action if the current memcached instance's precache level is lower.
	 */
	private function preCacheAction($level, $callback) {
		if ($this->preCacheLevel >= $level) return;

		$callback($this->cache);

		$this->preCacheLevel = $level;
		$this->updated = true;
	}
}

$precache = new PreCache($cache);
