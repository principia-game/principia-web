<?php
require('lib/common.php');

$page = (int)($_GET['page'] ?? 1);
$fid = (int)($_GET['id'] ?? 0);
$uid = (int)($_GET['user'] ?? 0);

$topbot = [];

$offset = (($page - 1) * $tpp);
$isread = $threadsread = '';

if ($log) {
	$isread = ($log ? ', (NOT (r.time<t.lastdate OR isnull(r.time)) OR t.lastdate<fr.time) isread ' : '');
	$threadsread = ($log ? "LEFT JOIN z_threadsread r ON (r.tid=t.id AND r.uid=$userdata[id]) "
		."LEFT JOIN z_forumsread fr ON (fr.fid=f.id AND fr.uid=$userdata[id]) " : '');
}

$ufields = userfields('u1', 'u1') . "," . userfields('u2', 'u2') . ",";
if ($fid) {
	if ($log) {
		$forum = fetch("SELECT f.*, r.time rtime FROM z_forums f LEFT JOIN z_forumsread r ON (r.fid = f.id AND r.uid = ?) "
			. "WHERE f.id = ? AND ? >= minread", [$userdata['id'], $fid, $userdata['powerlevel']]);
		if (!$forum['rtime']) $forum['rtime'] = 0;

		$isread = ", (NOT (r.time<t.lastdate OR isnull(r.time)) OR t.lastdate<'$forum[rtime]') isread";
		$threadsread = "LEFT JOIN z_threadsread r ON (r.tid=t.id AND r.uid=$userdata[id])";
	} else
		$forum = fetch("SELECT * FROM z_forums WHERE id = ? AND ? >= minread", [$fid, $userdata['powerlevel']]);

	if (!isset($forum['id'])) error("404", "Forum does not exist.");

	$title = $forum['title'];

	$threads = query("SELECT $ufields t.* $isread FROM z_threads t
			LEFT JOIN users u1 ON u1.id = t.user
			LEFT JOIN users u2 ON u2.id = t.lastuser
			$threadsread
			WHERE t.forum = ?
			ORDER BY t.sticky DESC, t.lastdate DESC
			LIMIT ?,?",
		[$fid, $offset, $tpp]);

	$topbot = [
		'title' => $forum['title']
	];
	if ($userdata['powerlevel'] >= $forum['minthread'])
		$topbot['actions'] = [['href' => "newthread?id=$fid", 'title' => 'New thread']];

} elseif ($uid) {
	$user = fetch("SELECT name FROM users WHERE id = ?", [$uid]);

	if (!$user) error("404", "User does not exist.");

	$title = "Threads by ".$user['name'];

	$threads = query("SELECT $ufields t.*, f.id fid $isread, f.title ftitle FROM z_threads t
			LEFT JOIN users u1 ON u1.id = t.user
			LEFT JOIN users u2 ON u2.id = t.lastuser
			LEFT JOIN z_forums f ON f.id = t.forum
			$threadsread
			WHERE t.user = ? AND ? >= minread
			ORDER BY t.sticky DESC, t.lastdate DESC
			LIMIT ?,?",
		[$uid, $userdata['powerlevel'], $offset, $tpp]);

	$forum['threads'] = result("SELECT count(*) FROM z_threads t
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE t.user = ? AND ? >= minread",
		[$uid, $userdata['powerlevel']]);

	$topbot = [
		'breadcrumb' => [['href' => "/user/$uid", 'title' => $user['name']]],
		'title' => 'Threads'
	];
} elseif (isset($_GET['time']) && $time = $_GET['time']) {
	$mintime = ($time > 0 && $time <= 2592000 ? time() - $time : 86400);

	$title = 'Latest threads';

	$threads = query("SELECT $ufields t.*, f.id fid $isread, f.title ftitle
			FROM z_threads t
			LEFT JOIN users u1 ON u1.id = t.user
			LEFT JOIN users u2 ON u2.id = t.lastuser
			LEFT JOIN z_forums f ON f.id = t.forum
			$threadsread
			WHERE t.lastdate > ? AND ? >= f.minread
			ORDER BY t.lastdate DESC
			LIMIT ?,?",
		[$mintime, $userdata['powerlevel'], $offset, $tpp]);

	$forum['threads'] = result("SELECT count(*) FROM z_threads t
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE t.lastdate > ? AND ? >= f.minread",
		[$mintime, $userdata['powerlevel']]);

} else {
	error("404", "Forum does not exist.");
}

$showforum = $time ?? $uid;

$fpagelist = '';
if ($forum['threads'] > $tpp) {
	$furl = "forum?";
	if ($fid)	$furl .= "id=$fid";
	if ($uid)	$furl .= "user=$uid";
	if ($time)	$furl .= "time=$time";
	$fpagelist = '<br>'.pagelist($forum['threads'], $tpp, $furl, $page, true);
}

$twig = _twigloader();
echo $twig->render('forum.twig', [
	'fid' => $fid,
	'title' => $title,
	'threads' => $threads,
	'showforum' => $showforum,
	'topbot' => $topbot,
	'fpagelist' => $fpagelist,
	'uid' => $uid ?? null,
	'time' => $time ?? null
]);
