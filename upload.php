<?php
require('lib/common.php');

// Kaitai runtime & data
require('lib/kaitai/plvl.php');

// Load level.
try {
	$level = Plvl::fromFile($_FILES['xFxIax']['tmp_name']);
} catch (Kaitai\Struct\Error\KaitaiError $e) {
	die('garbled level or garbled kaitai');
}

$platform = extractPlatform($_SERVER['HTTP_USER_AGENT']);

//include('upload_debug.php');

$nextId = result("SELECT id FROM levels ORDER BY id DESC LIMIT 1") + 1;

// Move uploaded level file to the levels directory.
if (!move_uploaded_file($_FILES['xFxIax']['tmp_name'], "levels/$nextId.plvl")) {
	die("let's look for some chips instead");
}

// TODO: Some way to check who is uploading the level. For the time being, every level is uploaded by user ID 1.
query("INSERT INTO levels (cat, title, description, author, time, derivates, hidden, platform) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
	[catConvert($level->type()), $level->name(), $level->descr(), 1, time(), $level->allowDerivatives(), $level->visibility(), $platform]);

// Print the ID of the uploaded level. This is required to display the "Level published!" box.
print($nextId);

