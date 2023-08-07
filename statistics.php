<?php
require('lib/common.php');

function runningTotal($table, $orderfield) {
	query("SET @runningTotal = 0;");
	return query(
		"SELECT $orderfield, num_interactions,
    		@runningTotal := @runningTotal + totals.num_interactions AS runningTotal
		FROM
			(SELECT FROM_UNIXTIME($orderfield) AS $orderfield, COUNT(*) AS num_interactions
				FROM $table AS e
				GROUP BY DATE(FROM_UNIXTIME(e.$orderfield))) totals
		ORDER BY $orderfield");
}

$levelGraph = runningTotal('levels', 'time');
$userGraph = runningTotal('users', 'joined');
$commentGraph = runningTotal('comments', 'time');

$twig = twigloader();
echo $twig->render('statistics.twig', [
    'level_graph' => $levelGraph,
    'user_graph' => $userGraph,
	'comment_graph' => $commentGraph,
]);
