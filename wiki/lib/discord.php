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
 *
 * @param string $text
 * @return void
 */
function discordSafeText($text) {
	return ($text ? preg_replace('/(`|@)/', '', $text) : null);
}

/**
 * Trigger the new wiki edit webhook.
 *
 * @param array $data Array with the necessary data.
 */
function wikiEditHook($data) {
	global $webhookWiki, $domain;

	$webhook = new Client($webhookWiki);

	$desc = discordSafeText($data['description']);
	if ($desc)
		$desc = '`'.$desc.'`';

	if ($data['revision'] > 1) {
		$moreinfo = sprintf(
			"([diff](<%s/wiki/%s?action=diff&prev=%s&next=%s>))",
		$domain, $data['page_slugified'], $data['revision']-1, $data['revision']);
	} else
		$moreinfo = "*(New)*";

	$msg = sprintf(
		"**[%s](<%s/user/%s>)** edited [%s](<%s/wiki/%s>) %s %s",
	$data['u_name'], $domain, $data['u_id'],
	$data['page'], $domain, $data['page_slugified'],
	$moreinfo, $desc);

	$webhook->message($msg)->send();
}

/**
 * Trigger the new wiki edit webhook on bulk imports.
 *
 * @param array $data Array with the necessary data.
 */
function wikiImportHook($data) {
	global $webhookWiki;

	$webhook = new Client($webhookWiki);

	$msg = sprintf(
		"**%d pages** have been imported automatically from script (total **%d bytes**)",
	$data['pages'], $data['size']);

	$webhook->message($msg)->send();
}
