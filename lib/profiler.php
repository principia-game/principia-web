<?php

class Profiler {
	private $starttime;

	function __construct() {
		$this->starttime = microtime(true);
	}

	function getStats() {
		printf("Rendered in %1.3fs with %dKB memory used", microtime(true) - $this->starttime, memory_get_usage(false) / 1024);
	}
}