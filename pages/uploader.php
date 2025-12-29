<?php
if (!userCanUploadFiles($userdata))
	error('403', "You do not have permission to upload files yet.");

if (isset($_POST['action']))
	require('pages/uploader/upload.php');
else
	require('pages/uploader/index.php');
