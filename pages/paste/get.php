<?php
$id = $path[2] ?? null;

$paste = getPasteById($id);

if (!$paste)
	error('404');

if ($paste['visibility'] == PASTE_VISIBILITY_PRIVATE && (!$log || $userdata['id'] != $paste['user']))
	error('404');

twigloader()->display('paste/get.twig', [
	'paste' => $paste
]);
