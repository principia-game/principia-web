<?php
$message = trim($_POST['message'] ?? '');

insertInto('chat_messages', [
	'user' => $userdata['id'],
	'message' => $message,
	'time' => time()
]);

echo json_encode(['status' => 'ok']);
