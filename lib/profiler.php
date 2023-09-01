<?php

class Profiler {
	private $starttime;

	function __construct() {
		$this->resetTime();
	}

	function resetTime() {
		$this->starttime = microtime(true);
	}

	function getStats() {
		printf("Rendered in %1.3fs with %dKB memory used", $this->getTime(), $this->getMemUsage());
	}

	function getTime() {
		return microtime(true) - $this->starttime;
	}

	function getMemUsage() {
		return memory_get_usage(false) / 1024;
	}
}
