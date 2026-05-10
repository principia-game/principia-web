<?php

use \DiscordWebhooks\Client;
use \DiscordWebhooks\Embed;

/**
 * Trigger the new forum post webhook.
 */
function newForumPostHook($post, $mode = 'reply') {
	$content = $post['message'];

	$content = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\](.*)\[\/quote\]'si", '', $content);

	// dirty description truncating
	if (strlen($content) > 250) {
		$content = wordwrap($content, 250);
		$content = substr($content, 0, strpos($content, "\n")) . '...';
	}

	$webhook = new Client();
	$mbd = new Embed();

	$mbd->title($post['title'].($mode == 'thread' ? ' (New Thread)' : ''))
		->description($content)
		->url(sprintf("%s/forum/thread?pid=%s#%s", DOMAIN, $post['pid'], $post['pid']))
		->timestamp(date(DATE_ATOM))
		->color("235AA3")
		->footer("New forum posts")
		->author(
			$post['u_name'],
			sprintf("%s/user/%s", DOMAIN, $post['u_id'])
		);

	try {
		$webhook->embed($mbd)->send(WEBHOOK_FORUM_DISC)->send(WEBHOOK_FORUM_FLUX);
	} catch (Exception $e) {
		trigger_error("Failed to send new forum post webhook: " . $e->getMessage(), E_USER_WARNING);
	}
}
