<?php

class Profiler {
	private $starttime;

	function __construct() {
		$this->starttime = microtime(true);
	}

	function getStats() {
		printf("%dKB used @ %1.3f secs", memory_get_usage(false) / 1024, microtime(true) - $this->starttime);
	}
}