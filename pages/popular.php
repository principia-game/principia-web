<?php

$levels = query("SELECT l.id, l.title, $userfields
		FROM levels l JOIN users u ON l.author = u.id
		WHERE l.visibility = 0 ORDER BY l.downloads DESC, l.id DESC LIMIT $lpp");

echo twigloader()->render('popular.twig', ['levels' => fetchArray($levels)]);