<?php

$lastId = (int)($_GET['last_id'] ?? 0);

$messages = [];
foreach (getMessages($lastId) as $msg)
	$messages[] = [
		'id' => $msg['id'],
		'user' => [
			'id' => $msg['u_id'],
			'name' => $msg['u_name'],
			'customcolor' => $msg['u_customcolor']
		],
		'message' => markdownInline($msg['message']),
		'time' => $msg['time']
	];

if (count($messages) == 0)
	http_response_code(204);
else
	jsonResponse($messages);
