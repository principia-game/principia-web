<?php

if (isset($path[2]))
	switch ($path[2]) {
		case 'send':
			require('pages/chat/send.php');
			exit;
		case 'fetch':
			require('pages/chat/fetch.php');
			exit;
		default:
			error('404');
	}

twigloader()->display('chat.twig');
