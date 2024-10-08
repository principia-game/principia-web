<?php

function lvledit($level, $method, $value = null) {
	// Sanity check
	if (!file_exists('tools/lvledit'))
		trigger_error('lvledit binary not found', E_USER_ERROR);

	$levelfile = sprintf('data/levels/%d.plvl', $level);
	if ($value !== null) {
		$value = escapeshellarg($value);

		$cmd = sprintf(
			'echo -n %s | ./tools/lvledit %s --%s',
		$value, $levelfile, $method);

		exec($cmd);
	} else {
		$cmd = sprintf(
			'./tools/lvledit %s --%s',
		$levelfile, $method);

		exec($cmd, $output);
		return implode("\n", $output);
	}
}

/**
 * Get the highest level version lvledit is built to handle
 */
function lvleditGetBuiltVersion() {

	exec('./tools/lvledit --get-built-level-version', $output);
	return implode("\n", $output);
}
