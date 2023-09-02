<?php
$action = $_GET['action'] ?? '';

//mark forum read
if ($log && $action == 'markread') {
	$fid = $_GET['fid'];
	if ($fid != 'all') {
		//delete obsolete threadsread entries
		query("DELETE r FROM z_threadsread r LEFT JOIN z_threads t ON t.id = r.tid WHERE t.forum = ? AND r.uid = ?", [$fid, $userdata['id']]);
		//add new forumsread entry
		query("REPLACE INTO z_forumsread VALUES (?,?,?)", [$userdata['id'], $fid, time()]);
	} else {
		//mark all read
		query("DELETE FROM z_threadsread WHERE uid = ?", [$userdata['id']]);
		query("REPLACE INTO z_forumsread (uid,fid,time) SELECT ?, f.id, ? FROM z_forums f", [$userdata['id'], time()]);
	}
	redirect('/forum/');
}

$categs = query("SELECT id,title FROM z_categories ORDER BY ord,id");
while ($c = $categs->fetch())
	$categ[$c['id']] = $c['title'];

$forums = query("SELECT f.*, ".($log ? "r.time rtime, " : '').userfields('u', 'u')." "
		. "FROM z_forums f "
		. "LEFT JOIN users u ON u.id=f.lastuser "
		. "LEFT JOIN z_categories c ON c.id=f.cat "
		. ($log ? "LEFT JOIN z_forumsread r ON r.fid = f.id AND r.uid = ".$userdata['id'] : '')
		. " WHERE ? >= f.minread "
		. " ORDER BY c.ord,c.id,f.ord,f.id ",
		[$userdata['rank']]);

echo twigloaderForum()->render('index.twig', [
	'forums' => $forums,
	'categories' => $categ
]);