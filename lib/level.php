<?php

$lvl_example = [
	'id' => 1,
	'title' => 'Example Level',
	'u_id' => 1,
	'u_name' => 'null',
];

function type_to_cat($type) {
	switch ($type) {
		case 'custom':		return 1;
		case 'adventure':	return 2;
		case 'puzzle':		return 3;
	}
}

function cat_to_type($cat) {
	switch ($cat) {
		case 1:		return 'custom';
		case 2:		return 'adventure';
		case 3:		return 'puzzle';
	}
}

function catConvert($cat) {
	switch ((int)$cat) {
		case 0:		return 3;
		case 1:		return 2;
		case 2:		return 1;
	}
}

function cmtTypeToNum($type) {
	switch ($type) {
		case 'level':	return 1;
		case 'news':	return 2;
		case 'contest':	return 3;
		case 'chat':	return 5;
	}
}

/**
 * Create a level box.
 *
 * @param array $lvl Level information. For an example list of fields, check $lvl_example.
 * @return string Created level box.
 */
function level($lvl, $featuredtext = '') {
	// TODO: rewrite this entire function...
	$featured = ($featuredtext ? '<span class="featured small">'.$featuredtext.'</span>' : '');
	$img = (!$lvl['locked'] ? "levels/thumbs/{$lvl['id']}.jpg" : 'assets/locked_thumb.svg');
	return <<<HTML
<div class="level" id="l-{$lvl['id']}">
	<a class="lvlbox_top" href="level.php?id={$lvl['id']}">
		<div>
			<img src="$img" id="icon">
			$featured
			<span>{$lvl['title']}</span>
		</div>
	</a>
	<a class="user" href="user.php?id={$lvl['u_id']}"><span class="t_user">{$lvl['u_name']}</span></a>
</div>
HTML;
}

/**
 * Extract the platform from a user agent string.
 * This is supposed to be used for getting the platform a level was uploaded from.
 *
 * @param string $ua User agent
 * @return string Platform.
 */
function extractPlatform($ua) {
	preg_match('/\((\w+)\)/', $ua, $matches);
	if (isset($matches[1])) {
		return $matches[1];
	} else {
		throw new Exception('No platform found (input is probably garbled)');
	}
}
