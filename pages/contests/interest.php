<?php

// ISO week, monday to sunday
$startOfWeek = date('U', strtotime('monday this week'));
$endOfWeek = date('U', strtotime('sunday this week'));

$existingInterest = false;
if ($log) {
	$existingInterest = result("SELECT id FROM contests_interest WHERE user = ? AND time >= ? AND time <= ?",
		[$userdata['id'], $startOfWeek, $endOfWeek]);
}

if (isset($_POST['interest']) && $_POST['interest'] == 'yes' && $log && !$existingInterest) {
	insertInto('contests_interest', [
		'user' => $userdata['id'],
		'time' => time()
	]);

	$existingInterest = true;
}

$interestedUsers = result("SELECT COUNT(*) FROM contests_interest WHERE time >= ? AND time <= ?",
[$startOfWeek, $endOfWeek]);

$contestAlreadyRunning = count(getCurrentContests()->fetchAll()) > 0;

twigloader()->display('contests/interest.twig', [
	'contest_already_running' => $contestAlreadyRunning,
	'already_interested' => $existingInterest ? true : false,
	'interested_users' => $interestedUsers
]);
