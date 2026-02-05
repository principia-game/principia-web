<?php
$message = trim($_POST['message'] ?? '');

if (IS_BANNED) {
	http_response_code(403);
	echo json_encode(['status' => 'banned']);
	die();
}

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

// Temporary fix to break Discord mentions
$message = str_replace("@", "`@`", $message);

// Trigger Discord webhook
newChatMessageHook([
	'u_name' => $userdata['name'],
	'message' => $message
]);
