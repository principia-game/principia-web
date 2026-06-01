#!/usr/bin/php
<?php
// This script will post a random level from the community site archive onto Mastodon.
// It's meant to be run as a cron job with any given interval for how often it should post.

require __DIR__.'/../data/config.php';
require __DIR__.'/../lib/mastodon.php';
require __DIR__.'/../lib/mysql.php';

$mastodon = new MastodonAPI(MASTO_TOKEN, MASTO_BASE_URL);

while (true) {
	$level = fetch("SELECT l.*,u.id u_id,u.name u_name FROM archive_levels l JOIN archive_users u ON l.author = u.id ORDER BY RAND() LIMIT 1");

	if (!$level)
		continue;

	$thumb = __DIR__."/../data/archive/thumbs/{$level['id']}-0-0.jpg";

	// Just in case we end up with a weird level that doesn't have a thumbnail
	if (file_exists($thumb))
		break;
}


$status = sprintf(<<<TEXT
Title: %s
Author: %s
Published on: %s

https://principia-web.se/archive/level/%d
TEXT, $level['title'], $level['u_name'], date('j F Y', $level['time']), $level['id']);

print($status);

$response = $mastodon->uploadMedia([
	'file' => curl_file_create($thumb, 'image/jpg', "level_thumb_{$level['id']}.jpg"),
	'description' => "Thumbnail of the level."
]);

if (!isset($response['id'])) {
	print("Failed to upload media, aborting.\n");
	exit(1);
}

$mastodon->postStatus([
	'status'      => $status,
	'visibility'  => 'public',
	'language'    => 'en',
	'media_ids[]' => $response['id'],
]);
