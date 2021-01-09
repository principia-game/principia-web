<?php

// Functions related to Discord webhook stuff.

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

function newLevelHook($level) {
	global $webhook;

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
			"url": "https://principia-web.tk/level.php?id=%s",
			"timestamp": "%s",
			"color": 13056,
			"footer": {
				"text": "New uploaded levels"
			},
			"thumbnail": {
				"url": "https://principia-web.tk/levels/thumbs/%s.jpg"
			},
			"author": {
				"name": "%s",
				"url": "https://principia-web.tk/user.php?id=%s"
			}
		}]
	}
JSON
	, $level['name'], $level['description'], $level['id'], date(DATE_ISO8601), $level['id'], $level['u_name'], $level['u_id']), true);

	discordmsg($msg, $webhook);
}
