<?php

// Change back to forum and include forum-specific code
foreach (glob("lib/forum/*.php") as $filename)
	require_once($filename);

// pages/threads per page
$ppp = 20;
$tpp = 20;
