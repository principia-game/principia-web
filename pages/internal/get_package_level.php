<?php
$level = $_GET['i'] ?? null;
$levelpath = sprintf('data/packages/levels/%d.plvl', $level);

if (!$level || !file_exists($levelpath))
	offerFile('static/assets/null.plvl', 'not-found');

offerFile($levelpath, $level.'.plvl');
