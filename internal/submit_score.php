<?php
chdir('../');
require('lib/common.php');

$level = (int)($_POST['lvl_id'] ?? null);

$progfile = $_FILES['data_bin'];

if (!$level || !$progfile || $progfile['name'] != 'data.bin') die("Cheatin'?");

if (!$log) {
	header("X-Error-Message: You need to be signed in to submit your score.");
	header("X-Error-Action: 1"); // Display login dialog
	die();
}

$output = exec(sprintf(
	'./tools/progress-get %s %d',
$progfile['tmp_name'], $level));

$result = explode(',', $output);

// TODO: Completion doesn't necessary mean you're eligble for highscore submission,
// see "Submit on game over" level property. Will need database-level level flags at some point.
//if ($result[0] == 1) {
	$lastscore = result("SELECT score FROM leaderboard WHERE level = ? AND user = ?", [$level, $userdata['id']]);

	if ($lastscore === false) {
		insertInto('leaderboard', [
			'level' => $level, 'user' => $userdata['id'], 'score' => $result[2]
		]);

		header('x-notify-message: Successfully submitted score!');
	} else {
		if ($result[2] > $lastscore) {
			query("UPDATE leaderboard SET score = ? WHERE level = ? AND user = ?",
				[$result[2], $level, $userdata['id']]);

			header('x-notify-message: Successfully submitted score!');
		} else
			header('x-notify-message: You already have a highscore better than this.');
	}
//}
