<?php

$contestdata = query("SELECT id,title FROM contests WHERE time_from >= NOW() ORDER BY id DESC");

$archivedContests = query("SELECT id,title FROM archive_contests ORDER BY id DESC");

twigloader()->display('contests.twig', [
	'contests' => fetchArray($contestdata),
	'archived_contests' => fetchArray($archivedContests)
]);
