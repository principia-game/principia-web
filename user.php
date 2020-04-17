<?php
include('lib/common.php');
pageheader();

if (isset($_GET['id'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE id = ?", [$_GET['id']]);
} else if (isset($_GET['name'])) {
	$userpagedata = fetch("SELECT * FROM users WHERE name = ?", [$_GET['name']]);
} else {
	// todo: we should have a error function
	die("no user specified");
}

$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.author = ? ORDER BY l.id DESC",
	[$userpagedata['id']]);

printf('<h2>%s</h2>', $userpagedata['name']);

while ($record = $levels->fetch()) {
	echo level($record) . ' ';
}

echo '<br><br>';
pagefooter();