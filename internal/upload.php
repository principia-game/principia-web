<?php
chdir('../');
require('lib/common.php');

#require('upload_debug.php');

if (!$log || $userdata['rank'] < 0)
	die('-100');

// Kaitai runtime & data
require('lib/kaitai/plvl.php');

// Load level.
$level = Plvl::fromFile($_FILES['xFxIax']['tmp_name']);

$platform = extractPlatform($useragent);

$cid = $level->communityId();

// If community id exists, get some level info we need
$leveldata = fetch("SELECT cat, author, revision, platform FROM levels WHERE id = ?", [$cid]);

// Check if we should update existing level
$updatelevel = false;
if ($leveldata && $userdata['id'] == $leveldata['author'])
	$updatelevel = true;
else {
	// Hängsle och livrem... Check if author has a level of the same name, if it is somehow
	// missing community_id or it's invalid (I fucking hate users why do they have to be so
	// user-y and break everything I try to make)

	$existinglevel = result("SELECT id FROM levels WHERE title = ? AND author = ?",
		[$level->name(), $userdata['id']]);

	if ($existinglevel) {
		$cid = $existinglevel;
		$updatelevel = true;

		$leveldata = fetch("SELECT cat, author, revision, platform FROM levels WHERE id = ?", [$cid]);
	}
}

// Preparations for if we do not update a level
if (!$updatelevel) {
	// New levels' ID should be the next one available
	$cid = result("SELECT id FROM levels ORDER BY id DESC LIMIT 1") + 1;

	// rate-limit new level uploading to once every 30 seconds
	$latestLevelTime = result("SELECT time FROM levels WHERE author = ? ORDER BY time DESC LIMIT 1", [$userdata['id']]);
	if (time() - $latestLevelTime < 30 && $userdata['rank'] < 2) {
		trigger_error(sprintf('%s tried to upload a level too quickly!', $userdata['name']), E_USER_NOTICE);
		die('-103');
	}
} else {
	// Preparations for if we update a level

	// back up previous revision level ...
	rename("data/levels/$cid.plvl", sprintf('data/backup/levels/%s.plvl.bak.%s', $cid, $leveldata['revision']));
	// ... and thumb
	if (file_exists("data/thumbs/$cid.jpg")) {
		rename("data/thumbs/$cid.jpg", sprintf('data/backup/thumbs/%s.jpg.bak.%s', $cid, $leveldata['revision']));

		// ... and low thumb
		if (file_exists("data/thumbs_low/$cid.jpg")) {
			rename("data/thumbs_low/$cid.jpg", sprintf('data/backup/thumbs_low/%s.jpg.bak.%s', $cid, $leveldata['revision']));
		}
	}
}

// Move uploaded level file to the levels directory.
if (!move_uploaded_file($_FILES['xFxIax']['tmp_name'], "data/levels/$cid.plvl")) {
	trigger_error("Could not move level file to levels folder, check permissions", E_USER_WARNING);
	die("-");
}

lvledit($cid, 'set-community-id', $cid);

if ($updatelevel) {
	$fields = [
		'cat'			=> catConvert($level->type()),
		'title'			=> $level->name(),
		'description'	=> $level->descr(),
		'derivatives'	=> $level->allowDerivatives(),
		'visibility'	=> $level->visibility(),
		'revision'		=> $leveldata['revision'] + 1,
		'revision_time'	=> time(),
		'platform'		=> $platform
	];

	$tmp = updateRowQuery($fields);

	$tmp['placeholders'][] = $cid;
	query("UPDATE levels SET ".$tmp['fieldquery']." WHERE id = ?", $tmp['placeholders']);

} else {
	// User level count has changed, invalidate cache
	$cachectrl->invLevelCount($userdata['id']);

	$params = [
		catConvert($level->type()),
		$level->name(), $level->descr(),
		$userdata['id'],
		time(),
		$level->allowDerivatives(),
		$level->visibility(),
		$platform,
		$level->parentId() ?? null];

	query("INSERT INTO levels (cat, title, description, author, time, derivatives, visibility, platform, parent) VALUES (?,?,?,?,?,?,?,?,?)",
		$params);

}

// Print the ID of the uploaded level. This is required to display the "Level published!" box.
print($cid);

// if we got a webhook url, send new level info to discord webhook
if (!$updatelevel && $webhookLevel) {
	$webhookdata = [
		'id' => $cid,
		'name' => $level->name(),
		'description' => $level->descr(),
		'u_id' => $userdata['id'],
		'u_name' => $userdata['name']
	];

	newLevelHook($webhookdata);
}

// Latest levels has most likely changed, invalidate index cache.
$cachectrl->invIndex();
