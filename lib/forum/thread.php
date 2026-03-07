<?php

function newPost($data) {
	if (!isset($data['time']))
		$data['time'] = time();

	insertInto('z_posts', [
		'user' => $data['u_id'],
		'thread' => $data['thread'],
		'date' => $data['time']
	]);

	$pid = insertId();
	$data['pid'] = $pid;
	insertInto('z_poststext', [
		'id' => $pid,
		'text' => $data['message']
	]);

	query("UPDATE z_forums
			SET posts = posts + 1,
			lastdate = ?, lastuser = ?, lastid = ?
			WHERE id = ?",
		[$data['time'], $data['u_id'], $pid, $data['forum']]);

	query("UPDATE z_threads
			SET posts = posts + 1,
			lastdate = ?, lastuser = ?, lastid = ?
			WHERE id = ?",
		[$data['time'], $data['u_id'], $pid, $data['thread']]);

	query("UPDATE users SET posts = posts + 1 WHERE id = ?",
		[$data['u_id']]);

	if (!isset($data['forum'])) {
		// nuke entries of this thread in the "threadsread" table
		query("DELETE FROM z_threadsread WHERE tid = ? AND NOT (uid = ?)", [$data['thread'], $data['u_id']]);
	}

	newForumPostHook($data, isset($data['forum']) ? 'thread' : 'reply');

	return $pid;
}

function newThread($data) {
	if (!isset($data['time']))
		$data['time'] = time();

	insertInto('z_threads', [
		'title' => $data['title'],
		'forum' => $data['forum'],
		'user' => $data['u_id'],
	]);
	$tid = insertId();
	$data['thread'] = $tid;

	query("UPDATE z_forums SET threads = threads + 1 WHERE id = ?",
		[$data['forum']]);

	query("UPDATE users SET threads = threads + 1 WHERE id = ?",
		[$data['u_id']]);

	newPost($data);

	return $tid;
}
