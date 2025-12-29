<?php
if (!userCanUploadPastes($userdata))
	error('403', 'You do not have permission to upload pastes.');

if (isset($_POST['paste_content'])) {
	$fileId = null;
	while ($fileId === null || result("SELECT COUNT(*) FROM pastes WHERE pasteid = ?", [$fileId]) != 0)
		$fileId = generateId();


	insertInto('pastes', [
		'pasteid' => $fileId,
		'user' => $userdata['id'],
		'time' => time(),
		'visibility' => $_POST['visibility'],
		'title' => $_POST['title'] ?? null,
		'content' => $_POST['paste_content'],
	]);

	redirect('/paste/' . $fileId);
}

twigloader()->display('paste/upload.twig');
