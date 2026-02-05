<?php

$levels = fetchArray(query("SELECT l.id, l.title, @userfields
		FROM @levels l JOIN @users u ON l.author = u.id WHERE l.visibility = 0 ORDER BY id DESC"));

twigloader()->display('levellist.twig', [
	'levels' => $levels
]);
