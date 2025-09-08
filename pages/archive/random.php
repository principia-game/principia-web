<?php
$randomlevels = randomLevels(20);

twigloader()->display('archive/random.twig', [
	'levels' => $randomlevels
]);
