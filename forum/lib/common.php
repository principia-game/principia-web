<?php
$acmlm = true;

// Change directory to principia-web and include core principia-web code.
chdir('../');
require_once('conf/config.php'); // include principia-web config
require_once('vendor/autoload.php');
require_once('lib/common.php');

// Change back to forum and include forum-specific code
chdir('forum/');
foreach (glob("lib/*.php") as $filename)
	require_once($filename);

// pages/threads per page
$ppp = 20;
$tpp = 20;
