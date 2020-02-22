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
		case 'jupiter':		return '';
	}
}

/**
 * Create a level box.
 *
 * @param array $lvl Level information. For an example list of fields, check $lvl_example.
 * @return string Created level box.
 */
function level($lvl) {
	return <<<HTML
<div class="level" id="l-{$lvl['id']}">
	<a class="lvlbox_top" href="level.php?id={$lvl['id']}">
		<div>
			<img src="assets/placeholder.png" id="icon">
			<span>{$lvl['title']}</span>
		</div>
	</a>
	<a class="user" href="user.php?id={$lvl['u_id']}"><span class="t_user">{$lvl['u_name']}</span></a>
</div>
HTML;
}
