<?php

$action = $_POST['action'] ?? null;
$file = $_FILES['uploadedfile'] ?? null;
$description = $_POST['description'] ?? null;

if (!$log || !$file) redirect('./uploader');

const UPLOADER_EXT_BLACKLIST = [
	"html", "htm", "shtm", "shtml", "php", "php5", "htaccess", "htpasswd", "js", "aspx", "cgi",	// Exts that potentially could cause vulns on the server.
	"py", "exe", "com", "bat", "pif", "cmd", "lnk", "vbs", "msc", "stm", "htc",					// General scripting/program extensions that execute on Windows, bad idea to allow them.
	'bmp', 'avi',		// Wasteful media formats, hinder their usage.
];

$fname = $file['name'];
$temp = $file['tmp_name'];
$filesize = $file['size'];
$extension = strtolower(pathinfo($fname, PATHINFO_EXTENSION));

if (!isset($filesize) || $filesize == 0)
	error('403', 'No file given.');

if ($filesize > UPLOADER_MAX_SIZE)
	error('403', 'The file you uploaded is larger than the maximum allowed ('.sizeunit(UPLOADER_MAX_SIZE).'). '.
		'<br><br>If this is an image you could try to downscale it in an image editor such as GIMP.');

if (in_array($extension, UPLOADER_EXT_BLACKLIST))
	error('403', 'Uploaded file uses an extension that is not allowed.');

$newest = result("SELECT date FROM uploader_files WHERE user = ? ORDER BY date DESC LIMIT 1", [$userdata['id']]);
if ($newest >= time() - 60 && $userdata['rank'] < 3)
	error('403', "You're uploading files too fast, please wait a while before uploading again.");

if (totalUserUploadSize($userdata['id']) + $filesize > UPLOADER_USER_MAX_TOTAL_SIZE)
	error('403', 'You have reached your total upload limit. If you need more space, contact ROllerozxa.');

$fileId = null;
while ($fileId === null || result("SELECT COUNT(*) FROM uploader_files WHERE fileid = ?", [$fileId]) != 0)
	$fileId = generateId();

$returnCode = move_uploaded_file($temp, uploadPath($fileId, $fname));

if ($returnCode === false)
	error('Error', 'An error happened while trying to upload the file.<br>'
		.'Try again later or contact a staff member to let them know about it.');

insertInto("uploader_files", [
	'fileid' => $fileId,
	'filename' => $fname,
	'description' => $description,
	'user' => $userdata['id'],
	'date' => time()
]);

redirect('/uploads/'.$fileId.'.'.$extension);
