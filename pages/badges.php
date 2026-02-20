<?php

$badges = query("SELECT * FROM badges ORDER BY id");

twigloader()->display('badges.twig', [
	'badges' => $badges
]);
