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
		$this->cache->delete('idx_anp');
		$this->cache->delete('idx_adv');
		$this->cache->delete('idx_feat');
	}

	/**
	 * Invalidate top rated levels on index
	 */
	public function invIndexTop() {
		$this->cache->delete('idx_top');
	}

	/**
	 * Invalidate comments on index
	 */
	public function invIndexComments() {
		$this->cache->delete('idx_cmnts');
	}

	/**
	 * Invalidate the level count for an user
	 */
	public function invLevelCount($user) {
		$this->cache->delete("levelcount_{$user}");
	}
}

$cachectrl = new CacheControl($cache);
