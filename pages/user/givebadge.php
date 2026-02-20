<?php

if (!IS_ADMIN) error(403);

if (isset($_POST['action'])) {
	$badge = (int)$_POST['badge'];
	$comment = $_POST['comment'] ?: null;

	insertInto('user_badges', [
		'user' => $id,
		'badge' => $badge,
		'comment' => $comment
	]);

	redirect('/user/%d/badges', $id);
}

twigloader()->display('user/givebadge.twig', [
	'user' => $user
]);
