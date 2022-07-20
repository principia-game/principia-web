<?php

class CacheControl {
	private $cache;

	function __construct($cache) {
		$this->cache = $cache;
	}

	/**
	 * Aggressively invalidate index cache if any data has been modified.
	 */
	public function invIndex() {
		$this->cache->deleteHash('idx_news');
		$this->cache->deleteHash('idx_anp');
		$this->cache->deleteHash('idx_adv');
		$this->cache->deleteHash('idx_feat');
	}

	/**
	 * Invalidate top rated levels on index
	 */
	public function invIndexTop() {
		$this->cache->deleteHash('idx_top');
	}
}

$cachectrl = new CacheControl($cache);
