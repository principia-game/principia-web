<?php
chdir('../');
require('lib/common.php');

$level = (int)($_POST['lvl_id'] ?? null);

$progfile = $_FILES['data_bin'];

if (!$level || !$progfile || $progfile['name'] != 'data.bin') die("Cheatin'?");

$output = exec(sprintf(
	'./tools/progress-get %s %d',
$progfile['tmp_name'], $level));

$result = explode(',', $output);

if ($result[0] == 1) {
	$lastscore = result("SELECT score FROM leaderboard WHERE level = ? AND user = ?", [$level, $userdata['id']]);

	if ($lastscore === false) {
		query("INSERT INTO leaderboard (level, user, score) VALUES (?,?,?)",
			[$level, $userdata['id'], $result[2]]);

		header('x-notify-message: Successfully submitted score!');
	} else {
		if ($result[2] > $lastscore) {
			query("UPDATE leaderboard SET score = ? WHERE level = ? AND user = ?",
				[$result[2], $level, $userdata['id']]);

			header('x-notify-message: Successfully submitted score!');
		} else {
			header('x-notify-message: You already have a highscore better than this.');
		}
	}
}
