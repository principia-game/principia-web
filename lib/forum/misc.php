<?php

function moveThread($id, $forum, $close = 0) {
	if (!result("SELECT COUNT(*) FROM z_forums WHERE id = ?", [$forum])) return;

	$thread = fetch("SELECT forum, posts FROM z_threads WHERE id = ?", [$id]);
	query("UPDATE z_threads SET forum = ? WHERE id = ?", [$forum, $id]);

	$last1 = fetch("SELECT lastdate,lastuser,lastid FROM z_threads WHERE forum = ? ORDER BY lastdate DESC LIMIT 1", [$thread['forum']]);
	$last2 = fetch("SELECT lastdate,lastuser,lastid FROM z_threads WHERE forum = ? ORDER BY lastdate DESC LIMIT 1", [$forum]);
	if ($last1)
		query("UPDATE z_forums SET posts = posts - ?, threads = threads - 1, lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
			[$thread['posts'], $last1['lastdate'], $last1['lastuser'], $last1['lastid'], $thread['forum']]);

	if ($last2)
		query("UPDATE z_forums SET posts = posts + ?, threads = threads + 1, lastdate = ?, lastuser = ?, lastid = ? WHERE id = ?",
			[$thread['posts'], $last2['lastdate'], $last2['lastuser'], $last2['lastid'], $forum]);
}
