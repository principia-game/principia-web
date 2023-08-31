<?php

class Sitemap {
	private $pages;
	private $domain;

	function __construct($domain) {
		$this->domain = $domain;
		$this->pages = [];
	}

	function add($page) {
		$this->pages[] = $this->domain . $page;
	}

	function output() {
		header('Content-Type: text/plain');

		echo join("\n", $this->pages);
	}
}
