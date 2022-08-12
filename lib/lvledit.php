<?php

function lvledit($level, $method, $value = null) {
	// Sanity check
	if (!file_exists('tools/lvledit'))
		trigger_error('lvledit binary not found', E_USER_ERROR);

	if ($value) {
		$levelfile = sprintf('levels/%d.plvl', $level);
		$value = escapeshellarg($value);

		system(sprintf(
			'echo -n "%s" | ./tools/lvledit %s --%s',
		$value, $levelfile, $method));
	} else {
		die('TODO: get');
	}
}
