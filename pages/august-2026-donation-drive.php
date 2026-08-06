<?php

$donators = query("SELECT * FROM campaign_contrib WHERE campaign = 1 ORDER BY amount DESC");
$totalDonations = result("SELECT SUM(amount) AS total FROM campaign_contrib WHERE campaign = 1");

$goal = 1000;

twigloader()->display('august-2026-donation-drive.twig', [
	'total_donations' => $totalDonations,
	'goal' => $goal,
	'donation_progress' => min(100, round($totalDonations / $goal * 100)),
	'donators' => $donators
]);
