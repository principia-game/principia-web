#!/usr/bin/php
<?php

print("principia-web tools - fix-forum-post-revisions.php\n");
print("==================================================\n");
print("Populate forum post revisions.\n");

require('lib/common.php');

$posts = query("SELECT id FROM z_posts");

while ($post = $posts->fetch()) {
	$id = $post['id'];
	$rev = result("SELECT MAX(revision) FROM z_poststext WHERE id = ?", [$id]);

	query("UPDATE z_posts SET revision = ? WHERE id = ?", [$rev, $id]);
}
