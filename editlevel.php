<?php
require('lib/common.php');

$lid = $_GET['id'] ?? 0;

$level = fetch("SELECT $userfields l.* FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

if (!$level || ($userdata['powerlevel'] < 2 && $userdata['id'] != $level['author']))
	error('403', "Odd place to find yourself.");

if (isset($_POST['action'])) {
	$title = $_POST['title'] ?? '';
	$description = $_POST['description'] ?? '';

	lvledit($lid, 'set-name', $title);
	lvledit($lid, 'set-description', $description);

	// sanity check
	// (if this gets triggered there is probably something severely wrong, immediately error out and exit)
	if (lvledit($lid, 'get-name') != $title
	 || lvledit($lid, 'get-description') != $description) {
		echo 'error occured while editing metadata, please report this immediately';
		trigger_error("SEVERE LVLEDIT ERROR", E_USER_ERROR);
	}

	query("UPDATE levels SET title = ?, description = ? WHERE id = ?", [$title, $description, $lid]);

	redirect("/level.php?id=$lid");
}

$twig = twigloader();

echo $twig->render('editlevel.twig', [
	'lid' => $lid,
	'level' => $level
]);
