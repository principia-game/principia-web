<?php

use \DiscordWebhooks\Client;
use \DiscordWebhooks\Embed;

/**
 * Trigger the new forum post webhook.
 *
 * @param array $post Post array with the necessary data.
 */
function newForumPostHook($post, $mode = 'reply') {
	global $webhookForum, $domain;

	$post['content'] = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\](.*)\[\/quote\]'si", '', $post['content']);

	// dirty description truncating
	if (strlen($post['content']) > 200) {
		$post['content'] = wordwrap($post['content'], 500);
		$post['content'] = substr($post['content'], 0, strpos($post['content'], "\n")) . '...';
	}

	$webhook = new Client($webhookForum);
	$mbd = new Embed();

	$mbd->title($post['title'].($mode == 'thread' ? ' (New Thread)' : ''))
		->description($post['content'])
		->url(sprintf("%s/forum/thread?pid=%s#%s", $domain, $post['id'], $post['id']))
		->timestamp(date(DATE_ISO8601))
		->color(2316963)
		->footer("New forum posts")
		->author(
			$post['u_name'],
			sprintf("%s/user/%s", $domain, $post['u_id'])
		);

	$webhook->embed($mbd)->send();
}
