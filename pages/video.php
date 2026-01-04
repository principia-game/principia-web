<?php

$id = $path[2] ?? null;

$video = getVideoByYouTubeID($id);

if (!$video) error('404');

twigloader()->display('video.twig', [
	'video' => $video,
]);
