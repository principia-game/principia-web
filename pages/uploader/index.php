<?php
$notdeleted = !IS_MOD ? 'WHERE f.user = '.$userdata['id'] : '';
$files = query("SELECT f.*, @userfields
		FROM uploader_files f JOIN users u ON u.id = f.user
		$notdeleted ORDER BY date DESC");

twigloader()->display('uploader.twig', [
	'files' => $files
]);
