<?php
chdir('../');
require('lib/common.php');

#require('upload_debug.php');

if (!$log || $userdata['powerlevel'] < 0) {
	die('-100');
}

// Kaitai runtime & data
require('lib/kaitai/plvl.php');

// Load level.
//try {
	$level = Plvl::fromFile($_FILES['xFxIax']['tmp_name']);
//} catch (Kaitai\Struct\Error\KaitaiError $e) {
//	die('garbled level or garbled kaitai');
//}

$platform = extractPlatform($_SERVER['HTTP_USER_AGENT']);

if ($level->communityId()) { // level has a non-noll community_id, assume we're updating a level
	$cid = $level->communityId();

	// get some level info we need
	$leveldata = fetch("SELECT cat, author, revision, locked, platform FROM levels WHERE id = ?", [$cid]);

	// additional checks to prevent someone from accidentally overwriting a level unintentionally.
	// this might need to be tweaked as
	if (!$leveldata
		|| catConvert($level->type()) != $leveldata['cat']
		|| ($userdata['id'] != $leveldata['author'] && $userdata['powerlevel'] < 3)
		|| $leveldata['locked']) {
		// Throw an error and die, emulates the official community site's behavior of an incorrect community id (malicious or not)
		trigger_error(sprintf('%s tried to upload a level with an invalid community id (%s)', $userdata['name'], $cid), E_USER_NOTICE);
		die('-101');
	}

	// back up previous revision level ...
	rename("levels/$cid.plvl", sprintf('levels/backup/%s.plvl.bak.%s', $cid, $leveldata['revision']));
	// ... and thumb
	if (file_exists("levels/thumbs/$cid.jpg")) {
		rename("levels/thumbs/$cid.jpg", sprintf('levels/thumbs/backup/%s.jpg.bak.%s', $cid, $leveldata['revision']));

		// ... and low thumb
		if (file_exists("levels/thumbs/low/$cid.jpg")) {
			rename("levels/thumbs/low/$cid.jpg", sprintf('levels/thumbs/backup/%s.low.jpg.bak.%s', $cid, $leveldata['revision']));
		}
	}

	// Move uploaded level file to the levels directory.
	if (!move_uploaded_file($_FILES['xFxIax']['tmp_name'], "levels/$cid.plvl")) {
		die("let's look for some chips instead");
	}

	query("UPDATE levels SET title = ?, description = ?, derivatives = ?, locked = ?, revision = revision + 1, revision_time = ? WHERE id = ?",
		[$level->name(), $level->descr(), $level->allowDerivatives(), $level->visibility(), time(), $cid]);

	// Print the ID of the uploaded level. This is required to display the "Level published!" box.
	print($cid);
} else { // level has a noll community_id, assume we're uploading a new level
	// rate-limit new level uploading to once every 5 minutes
	$latestLevelTime = result("SELECT time FROM levels WHERE author = ? ORDER BY time DESC LIMIT 1", [$userdata['id']]);
	if (time() - $latestLevelTime < 5*60 && $userdata['powerlevel'] < 2) {
		trigger_error(sprintf('%s tried to upload a level too quickly!', $userdata['name']), E_USER_NOTICE);
		die('-103');
	}

	$nextId = result("SELECT id FROM levels ORDER BY id DESC LIMIT 1") + 1;

	// Move uploaded level file to the levels directory.
	if (!move_uploaded_file($_FILES['xFxIax']['tmp_name'], "levels/$nextId.plvl")) {
		die("let's look for some chips instead");
	}

	$parent = $level->parentId() ?? null;

	query("INSERT INTO levels (cat, title, description, author, time, derivatives, locked, platform, parent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
		[catConvert($level->type()), $level->name(), $level->descr(), $userdata['id'], time(), $level->allowDerivatives(), $level->visibility(), $platform, $parent]);

	// Print the ID of the uploaded level. This is required to display the "Level published!" box.
	print($nextId);

	// if we got a webhook url, send level info to discord webhook
	if ($webhook) {
		$webhookdata = [
			'id' => $nextId,
			'name' => $level->name(),
			'description' => $level->descr(),
			'u_id' => $userdata['id'],
			'u_name' => $userdata['name']
		];

		newLevelHook($webhookdata);
	}
}
