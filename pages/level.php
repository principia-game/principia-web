<?php

if (isset($_GET['id'])) redirectPerma('/level/%d', $_GET['id']);

$lid = (int)($path[2] ?? 0);

$level = getLevelById($lid);

if (!$level) error('404');

assert($lid == $level['id']);

if (isset($path[3]) && $path[3] == 'play') {
	header("Cross-Origin-Resource-Policy: same-origin");
	header("Cross-Origin-Embedder-Policy: require-corp");
	header("Cross-Origin-Opener-Policy: same-origin");

	twigloader()->display('play_level.twig', [
		'lid' => $lid,
		'level' => $level]);

	return;
}

$contestStatus = 0;

if (!IS_ARCHIVE && $log) {
	// like
	$hasLiked = result("SELECT COUNT(*) FROM likes WHERE user = ? AND level = ?", [$userdata['id'], $lid]) == 1 ? true : false;
	if (isset($_POST['vote'])) {
		if (!$hasLiked)
			likeLevel($lid, $userdata['id']);

		die();
	}

	// add to contest
	if (isset($_POST['addtocontest']))
		$contestStatus = addToContest($_POST['addtocontest'], $lid);

	// toggle lock
	if (isset($_GET['togglelock']) && ($level['author'] == $userdata['id'] || IS_MOD))
		$level['visibility'] = toggleLevelLock($level, $level['visibility']);

	// rerun webhook
	if (isset($_GET['rerunhook']) && IS_ADMIN)
		newLevelHook([
			'id' => $level['id'],
			'name' => $level['title'],
			'description' => $level['description'],
			'u_id' => $level['u_id'],
			'u_name' => $level['u_name']
		]);

	// delete level thumbnails
	if (isset($_GET['delthumb']) && IS_ADMIN)
		deleteLevelThumbs($lid);

	removeLevelNotifications($lid, $userdata['id']);

	incrementLevelView($level);
}

$context = [
	'lid' => $lid,
	'level' => $level,
	'derivatives' => fetchArray(getLevelDerivatives($lid)),
	'parentlevel' => getParentLevel($level),
	'comments' => getComments(IS_ARCHIVE ? 'archive/level' : 'level', $lid),
];

if (!IS_ARCHIVE) {
	$context = array_merge($context, [
		'has_liked' => $hasLiked ?? false,
		'contests' => fetchArray(getCurrentContests()),
		'contest_entered' => $contestStatus == 1,
		'already_entered' => $contestStatus == 2,
		'leaderboard' => fetchArray(getLeaderboard($lid))
	]);
}

twigloader()->display('level.twig', $context);
