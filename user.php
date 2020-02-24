<?php
include('lib/common.php');
pageheader();

$uid = (isset($_GET['id']) ? $_GET['id'] : null);

$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$uid]);

$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.author = ? ORDER BY l.id DESC LIMIT 5",
	[$uid]);

printf('<h2>%s</h2>', $userpagedata['name']);

while ($record = $levels->fetch()) {
	echo level($record) . ' ';
}

echo '<br><br>';
pagefooter();