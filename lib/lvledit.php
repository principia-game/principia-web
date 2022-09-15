<?php

function lvledit($level, $method, $value = null) {
	// Sanity check
	if (!file_exists('tools/lvledit'))
		trigger_error('lvledit binary not found', E_USER_ERROR);

	$levelfile = sprintf('levels/%d.plvl', $level);
	if ($value) {
		$value = escapeshellarg($value);

		$cmd = sprintf(
			'echo -n %s | ./tools/lvledit %s --%s',
		$value, $levelfile, $method);

		exec($cmd);
	} else {
		$value = escapeshellarg($value);

		$cmd = sprintf(
			'./tools/lvledit %s --%s',
		$levelfile, $method);

		return exec($cmd);
	}
}
