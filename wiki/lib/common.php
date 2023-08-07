<?php
$submodule = true;

// Change directory to principia-web and include core principia-web code.
chdir('../');
require_once('conf/config.php'); // include principia-web config
require_once('vendor/autoload.php');
require_once('lib/common.php');

// Change back to wiki and include wiki-specific code
chdir('wiki/');
foreach (glob("lib/*.php") as $filename)
	require_once($filename);
