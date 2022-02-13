<?php
chdir('../');
require('lib/common.php');

$level = isset($_GET['i']) ? (int)$_GET['i'] : null;
$levelpath = sprintf('levels/%d.plvl', $level);

if ($level > 1000000) { // Level loading overriding for the community archive
	$levelpath_archive = "level_archive/CommunityLevels.zip";
	if (file_exists($levelpath_archive)) {
		$zipFile = new \PhpZip\ZipFile();
		$zipFile->openFile($levelpath_archive);

		echo $zipFile->getEntryContents(sprintf('%d.plvl', $level - 1000000));
		die();
	}
}

if (!$level || !file_exists($levelpath)) {
	// Temporarily disabled due to package problems
	//header('HTTP/1.0 404 Not Found');
	echo readfile('internal/null.plvl');
	die();
}

query("UPDATE levels SET downloads = downloads + '1' WHERE id = ?", [$level]);

echo readfile($levelpath);