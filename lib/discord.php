<?php
// Functions related to Discord webhook stuff.

use \DiscordWebhooks\Client;
use \DiscordWebhooks\Embed;

$exampleWebhookData = [
	'id' => 1,
	'name' => 'not so smiley man',
	'description' => "finally\n\nnot so smiley man",
	'u_id' => 1,
	'u_name' => 'ROllerozxa'
];

/**
 * Trigger the new level webhook.
 */
function newLevelHook($level) {
	// dirty description truncating
	if (strlen($level['description']) > 500) {
		$level['description'] = wordwrap($level['description'], 500);
		$level['description'] = substr($level['description'], 0, strpos($level['description'], "\n")) . '...';
	}

	$webhook = new Client();
	$mbd = new Embed();

	$mbd->title($level['name'])
		->description($level['description'])
		->url(sprintf("%s/level/%s", DOMAIN, $level['id']))
		->timestamp(date(DATE_ATOM))
		->color("178017")
		->footer("New uploaded levels")
		->thumbnail(sprintf("%s/thumbs/%s.jpg", DOMAIN, $level['id']))
		->author(
			$level['u_name'],
			sprintf("%s/user/%s", DOMAIN, $level['u_id'])
		);

	try {
		$webhook->embed($mbd)->send(WEBHOOK_LEVEL_DISC)->send(WEBHOOK_LEVEL_FLUX);
	} catch (Exception $e) {
		trigger_error("Failed to send new level webhook: " . $e->getMessage(), E_USER_WARNING);
	}
}

function newChatMessageHook($message) {
	$webhook = new Client();
	$webhook->message(sprintf("<%s> %s", $message['u_name'], $message['message']));

	try {
		$webhook->send(WEBHOOK_CHAT_DISC)->send(WEBHOOK_CHAT_FLUX);
	} catch (Exception $e) {
		trigger_error("Failed to send new chat message webhook: " . $e->getMessage(), E_USER_WARNING);
	}
}
