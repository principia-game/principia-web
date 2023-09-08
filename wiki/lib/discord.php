<?php
use \DiscordWebhooks\Client;

$exampleWebhookData = [
	'page' => 'Some Cool Page',
	'page_slugified' => 'Some_Cool_Page',
	'description' => 'cool edit `@everyone',
	'revision' => 3,
	'u_id' => 1,
	'u_name' => 'ROllerozxa'
];

/**
 * Make text safe for raw use in Discord webhooks (strip backticks and at-symbols)
 */
function discordSafeText($text) {
	return ($text ? preg_replace('/(`|@)/', '', $text) : null);
}

/**
 * Trigger the new wiki edit webhook.
 */
function wikiEditHook($data) {
	$webhook = new Client(WEBHOOK_WIKI);

	$desc = discordSafeText($data['description']);
	if ($desc)
		$desc = '`'.$desc.'`';

	if ($data['revision'] > 1) {
		$moreinfo = sprintf(
			"([diff](<%s/wiki/%s?action=diff&prev=%s&next=%s>))",
		DOMAIN, $data['page_slugified'], $data['revision']-1, $data['revision']);
	} else
		$moreinfo = "*(New)*";

	$msg = sprintf(
		"**[%s](<%s/user/%s>)** edited [%s](<%s/wiki/%s>) %s %s",
	$data['u_name'], DOMAIN, $data['u_id'],
	$data['page'], DOMAIN, $data['page_slugified'],
	$moreinfo, $desc);

	$webhook->message($msg)->send();
}

/**
 * Trigger the new wiki edit webhook on bulk imports.
 *
 * @param array $data Array with the necessary data.
 */
function wikiImportHook($data) {
	$webhook = new Client(WEBHOOK_WIKI);

	$msg = sprintf(
		"**%d pages** have been imported automatically from script (total **%d bytes**)",
	$data['pages'], $data['size']);

	$webhook->message($msg)->send();
}
