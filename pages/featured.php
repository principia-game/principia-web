<?php

$featuredLevels = query("SELECT l.id,l.title, @userfields FROM featured f JOIN levels l on f.level = l.id JOIN users u ON l.author = u.id ORDER BY f.id DESC");

twigloader()->display('featured.twig', [
	'featured_levels' => fetchArray($featuredLevels)
]);
