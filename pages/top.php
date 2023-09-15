<?php

$levels = query("SELECT l.id id, l.title title, $userfields
		FROM levels l JOIN users u ON l.author = u.id
		WHERE l.visibility = 0 ORDER BY l.likes DESC, l.id DESC LIMIT ".LPP);

twigloader()->display('top.twig', ['levels' => fetchArray($levels)]);