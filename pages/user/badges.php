<?php

$badges = getUserBadges($id);

twigloader()->display('user/badges.twig', [
	'id' => $id,
	'user' => $user,
	'badges' => $badges
]);
