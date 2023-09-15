<?php

$contestdata = query("SELECT id,title FROM contests ORDER BY id DESC");

twigloader()->display('contests.twig', [
	'contests' => fetchArray($contestdata)
]);
