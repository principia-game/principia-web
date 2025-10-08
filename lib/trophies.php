<?php

function trophyLabel($name, $count) {
	$numTrophies = $count > 1 ? "<span class=\"num_trophees\">{$count}</span>" : '';

	return $count > 0 ? sprintf('<span class="trophee %s">%s</span>', $name, $numTrophies) : '';
}

/**
 * Encode trophy counts into a packed 32-bit integer.
 * Format: [unused (8 bits)][black (8 bits)][gold (8 bits)][silver (8 bits)]
 */
function encodeTrophies($silver, $gold, $black) {
    $silver = clamp($silver, 0, 255);
    $gold = clamp($gold, 0, 255);
    $black = clamp($black, 0, 255);

    return ($black << 16) | ($gold << 8) | $silver;
}

/**
 * Decode trophy integer into individual trophy counts, as a key-value array
 */
function decodeTrophies($t) {
    return [
        'silver' =>  $t        & 0xFF,
        'gold'   => ($t >> 8)  & 0xFF,
        'black'  => ($t >> 16) & 0xFF
    ];
}

function printTrophies($trophyValue) {
	$trophies = decodeTrophies($trophyValue);

	return trophyLabel('bdiamond', $trophies['black'])
		 . trophyLabel('gold', $trophies['gold'])
		 . trophyLabel('silver', $trophies['silver']);
}
