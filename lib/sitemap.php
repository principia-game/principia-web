<?php

class Sitemap {
	private $pages;
	private $domain;
	private $prefix;

	function __construct($domain) {
		$this->domain = $domain;
		$this->pages = [];
		$this->prefix = '';
	}

	function setPrefix($prefix) {
		if (!empty($this->pages))
			throw new Exception("Cannot set prefix after pages have been added.");

		$this->prefix = $prefix;
	}

	function add($page) {
		if (is_array($page))
			foreach ($page as $p)
				$this->add($p);
		else
			$this->pages[] = $this->domain . $this->prefix . $page;
	}

	function output() {
		header('Content-Type: text/plain');

		echo join("\n", $this->pages);
	}
}
