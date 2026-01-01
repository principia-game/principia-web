<?php
$message = trim($_POST['message'] ?? '');

insertInto('chat_messages', [
	'user' => $userdata['id'],
	'message' => $message,
	'time' => time()
]);

echo json_encode(['status' => 'ok']);

if (function_exists('fastcgi_finish_request')) {
	// flush all response data to client and finish request, user does not need to wait for webhook
	fastcgi_finish_request();
}

// Trigger Discord webhook
newChatMessageHook([
	'u_name' => $userdata['name'],
	'message' => $message
]);
