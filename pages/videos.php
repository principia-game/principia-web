<?php

twigloader()->display('videos.twig', [
	'videos' => getVideos(),
]);
