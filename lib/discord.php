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
 *
 * @param array $level Level array with the necessary data.
 */
function newLevelHook($level) {
	global $webhookLevel, $domain;

	// dirty description truncating
	if (strlen($level['description']) > 500) {
		$level['description'] = wordwrap($level['description'], 500);
		$level['description'] = substr($level['description'], 0, strpos($level['description'], "\n")) . '...';
	}

	$webhook = new Client($webhookLevel);
	$mbd = new Embed();

	$mbd->title($level['name'])
		->description($level['description'])
		->url(sprintf("%s/level/%s", $domain, $level['id']))
		->timestamp(date(DATE_ISO8601))
		->color(13056)
		->footer("New uploaded levels")
		->thumbnail(sprintf("%s/thumbs/%s.jpg", $domain, $level['id']))
		->author(
			$level['u_name'],
			sprintf("%s/user/%s", $domain, $level['u_id'])
		);

	$webhook->embed($mbd)->send();
}
