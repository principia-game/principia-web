<?php
$randomlevels = randomLevels(20);

twigloader()->display('random.twig', [
	'levels' => $randomlevels
]);
