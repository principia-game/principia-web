<?php

// Functions related to Discord webhook stuff.

/**
 * Function to trigger a Discord webhook.
 *
 * @param json $msg JSON payload
 * @param string $webhook Webhook URL
 * @return mixed Response from Discord.
 */
function discordmsg($msg, $webhook) {
	if ($webhook != "") {
		$ch = curl_init($webhook);
		$msg = "payload_json=" . urlencode(json_encode($msg))."";

		if (isset($ch)) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
	}
}

$exampleWebhookData = [
	'id' => 1,
	'name' => 'not so smiley man',
	'description' => "finally\\n\\nnot so smiley man",
	'u_id' => 1,
	'u_name' => 'ROllerozxa'
];

/**
 * Trigger the new level webhook.
 *
 * @param array $level Level array with the necessary data.
 */
function newLevelHook($level) {
	global $webhook, $domain;

	// dirty description truncating
	if (strlen($level['description']) > 500) {
		$level['description'] = wordwrap($level['description'], 500);
		$level['description'] = substr($level['description'], 0, strpos($level['description'], "\n")) . '...';
	}

	$level['description'] = str_replace("\n", "\\n", $level['description']);

	$msg = json_decode(sprintf(<<<JSON
	{
		"embeds": [{
			"title": "%s",
			"description": "%s",
			"url": "%s/level.php?id=%s",
			"timestamp": "%s",
			"color": 13056,
			"footer": {
				"text": "New uploaded levels"
			},
			"thumbnail": {
				"url": "%s/levels/thumbs/%s.jpg"
			},
			"author": {
				"name": "%s",
				"url": "%s/user.php?id=%s"
			}
		}]
	}
JSON
	, $level['name'], $level['description'], $domain, $level['id'], date(DATE_ISO8601), $domain, $level['id'], $level['u_name'], $domain, $level['u_id']), true);

	$response = discordmsg($msg, $webhook);
}
