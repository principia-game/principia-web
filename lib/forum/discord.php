<?php

use \DiscordWebhooks\Client;
use \DiscordWebhooks\Embed;

/**
 * Trigger the new forum post webhook.
 */
function newForumPostHook($post, $mode = 'reply') {
	$post['content'] = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\](.*)\[\/quote\]'si", '', $post['content']);

	// dirty description truncating
	if (strlen($post['content']) > 250) {
		$post['content'] = wordwrap($post['content'], 250);
		$post['content'] = substr($post['content'], 0, strpos($post['content'], "\n")) . '...';
	}

	$webhook = new Client(WEBHOOK_FORUM);
	$mbd = new Embed();

	$mbd->title($post['title'].($mode == 'thread' ? ' (New Thread)' : ''))
		->description($post['content'])
		->url(sprintf("%s/forum/thread?pid=%s#%s", DOMAIN, $post['id'], $post['id']))
		->timestamp(date(DATE_ATOM))
		->color("235AA3")
		->footer("New forum posts")
		->author(
			$post['u_name'],
			sprintf("%s/user/%s", DOMAIN, $post['u_id'])
		);

	$webhook->embed($mbd)->send();
}
