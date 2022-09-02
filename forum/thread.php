<?php
require('lib/common.php');

$page = (int)($_GET['page'] ?? 1);

$fieldlist = userfields('u', 'u') . ',' . userfields_post();

if (isset($_GET['id'])) {
	$tid = (int)$_GET['id'];
	$viewmode = "thread";
} elseif (isset($_GET['user'])) {
	$uid = (int)$_GET['user'];
	$viewmode = "user";
} elseif (isset($_GET['time'])) {
	$time = (int)$_GET['time'];
	$viewmode = "time";
} elseif (isset($_GET['pid'])) { // "link" support (i.e., thread.php?pid=999whatever)
	$pid = (int)$_GET['pid'];
	$numpid = fetch("SELECT t.id tid FROM z_posts p LEFT JOIN z_threads t ON p.thread = t.id WHERE p.id = ?", [$pid]);
	if (!$numpid) error("404", "Thread post does not exist.");

	$tid = result("SELECT thread FROM z_posts WHERE id = ?", [$pid]);
	$page = floor(result("SELECT COUNT(*) FROM z_posts WHERE thread = ? AND id < ?", [$tid, $pid]) / $ppp) + 1;
	$viewmode = "thread";
} else
	error("404", "Thread does not exist.");

$threadcreator = ($viewmode == "thread" ? result("SELECT user FROM z_threads WHERE id = ?", [$tid]) : 0);

$modact = '';

$act = $_POST['action'] ?? '';

if (isset($tid) && $log && $act && ($userdata['powerlevel'] > 2 ||
		($userdata['id'] == $threadcreator && $act == "rename" && $userdata['powerlevel'] > 0 && isset($_POST['title'])))) {

	if ($act == 'stick')	$modact = ',sticky=1';
	if ($act == 'unstick')	$modact = ',sticky=0';
	if ($act == 'close')	$modact = ',closed=1';
	if ($act == 'open')		$modact = ',closed=0';
	if ($act == 'trash')	moveThread($tid, $trashid, 1);
	if ($act == 'rename')	$modact = ",title=?";
	if ($act == 'move')		moveThread($tid, $_POST['arg']);
}

$offset = (($page - 1) * $ppp);

if ($viewmode == "thread") {
	if (!$tid) $tid = 0;

	$params = ($act == 'rename' ? [$_POST['title'], $tid] : [$tid]);

	query("UPDATE z_threads SET views = views + 1 $modact WHERE id = ?", $params);

	$thread = fetch("SELECT t.*, f.title ftitle, t.forum fid".($log ? ', r.time frtime' : '').' '
			. "FROM z_threads t LEFT JOIN z_forums f ON f.id=t.forum "
			. ($log ? "LEFT JOIN z_forumsread r ON (r.fid=f.id AND r.uid=$userdata[id]) " : '')
			. "WHERE t.id = ? AND ? >= f.minread",
			[$tid, $userdata['powerlevel']]);

	if (!isset($thread['id'])) error("404", "Thread does not exist.");

	//append thread's title to page title
	$title = $thread['title'];

	//mark thread as read
	if ($log && $thread['lastdate'] > $thread['frtime'])
		query("REPLACE INTO z_threadsread VALUES (?,?,?)", [$userdata['id'], $thread['id'], time()]);

	//check for having to mark the forum as read too
	if ($log) {
		$readstate = fetch("SELECT ((NOT ISNULL(r.time)) OR t.lastdate < ?) n FROM z_threads t LEFT JOIN z_threadsread r ON (r.tid = t.id AND r.uid = ?) "
			. "WHERE t.forum = ? GROUP BY ((NOT ISNULL(r.time)) OR t.lastdate < ?) ORDER BY n ASC",
			[$thread['frtime'], $userdata['id'], $thread['fid'], $thread['frtime']]);
		//if $readstate[n] is 1, MySQL did not create a group for threads where ((NOT ISNULL(r.time)) OR t.lastdate<'$thread[frtime]') is 0;
		//thus, all threads in the forum are read. Mark it as such.
		if ($readstate['n'] == 1)
			query("REPLACE INTO z_forumsread VALUES (?,?,?)", [$userdata['id'], $thread['fid'], time()]);
	}

	$posts = query("SELECT $fieldlist p.*, pt.text, pt.date ptdate, pt.revision cur_revision, t.forum tforum
			FROM z_posts p
			LEFT JOIN z_threads t ON t.id = p.thread
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			WHERE p.thread = ?
			GROUP BY p.id ORDER BY p.id
			LIMIT ?,?",
		[$tid, $offset, $ppp]);

} elseif ($viewmode == "user") {
	$user = fetch("SELECT * FROM users WHERE id = ?", [$uid]);

	if ($user == null) error("404", "User doesn't exist.");

	$title = "Posts by " . $user['name'];
	$posts = query("SELECT $fieldlist p.*, pt.text, pt.date ptdate, pt.revision cur_revision, t.id tid, f.id fid, t.title ttitle, t.forum tforum
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			LEFT JOIN z_threads t ON p.thread = t.id
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE p.user = ? AND ? >= f.minread
			ORDER BY p.id LIMIT ?,?",
		[$uid, $userdata['powerlevel'], $offset, $ppp]);

	$thread['posts'] = result("SELECT count(*) FROM z_posts p WHERE user = ?", [$uid]);
} elseif ($viewmode == "time") {
	$mintime = ($time > 0 && $time <= 2592000 ? time() - $time : 86400);

	$title = 'Latest posts';

	$posts = query("SELECT $fieldlist p.*, pt.text, pt.date ptdate, pt.revision cur_revision, t.id tid, f.id fid, t.title ttitle, t.forum tforum
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user=u.id
			LEFT JOIN z_threads t ON p.thread=t.id
			LEFT JOIN z_forums f ON f.id=t.forum
			WHERE p.date > ? AND ? >= f.minread
			ORDER BY p.date DESC
			LIMIT ?,?",
		[$mintime, $userdata['powerlevel'], $offset, $ppp]);

	$thread['posts'] = result("SELECT count(*) FROM z_posts WHERE date > ?", [$mintime]);
} else
	$title = '';

$pagelist = '';
if ($thread['posts'] > $ppp) {
	$furl = "thread.php?";
	if ($viewmode == "thread")	$furl .= "id=$tid";
	if ($viewmode == "user")	$furl .= "user=$uid";
	if ($viewmode == "time")	$furl .= "time=$time";
	$pagelist = '<br>'.pagelist($thread['posts'], $ppp, $furl, $page, true);
}

if ($viewmode == "thread") {
	$topbot = [
		'breadcrumb' => [['href' => 'forum.php?id='.$thread['forum'], 'title' => $thread['ftitle']]],
		'title' => $thread['title']
	];

	$faccess = fetch("SELECT id,minreply FROM z_forums WHERE id = ?",[$thread['forum']]);
	if ($faccess['minreply'] <= $userdata['powerlevel']) {
		if ($userdata['powerlevel'] > 1 && $thread['closed'])
			$topbot['actions'] = [['title' => 'Thread closed'],['href' => "newreply.php?id=$tid", 'title' => 'New reply']];
		else if ($thread['closed'])
			$topbot['actions'] = [['title' => 'Thread closed']];
		else
			$topbot['actions'] = [['href' => "newreply.php?id=$tid", 'title' => 'New reply']];
	}
} elseif ($viewmode == "user") {
	$topbot = [
		'breadcrumb' => [['href' => "/user.php?id=$uid", 'title' => $user['name']]],
		'title' => 'Posts'
	];
} elseif ($viewmode == "time") {
	$topbot = [];
	$time = $_GET['time'];
}

$modlinks = '';
if ($log && isset($tid) && ($userdata['powerlevel'] > 2 || ($userdata['id'] == $thread['user'] && !$thread['closed'] && $userdata['powerlevel'] > 0))) {
	$fmovelinks = $stick = $close = $trash = '';
	$link = "<a href=javascript:submitmod";
	if ($userdata['powerlevel'] > 2) {
		$stick = $link.($thread['sticky'] ? "('unstick')>Unstick" : "('stick')>Stick").'</a>';
		$close = '| '.$link.($thread['closed'] ? "('open')>Open" : "('close')>Close").'</a>';

		if ($thread['forum'] != $trashid)
			$trash = '| <a href=javascript:submitmod(\'trash\') onclick="trashConfirm(event)">Trash</a>';

		$edit = '| <a href="javascript:showrbox()">Rename</a> | <a href="javascript:showmove()">Move</a>';

		$fmovelinks = addslashes(forumlist($thread['forum']))
		.'<input type="submit" id="move" value="Submit" name="movethread" onclick="submitmove(movetid())">';
	} else {
		$edit = '<a href="javascript:showrbox()">Rename</a>';
	}

	$renamefield = '<input type="text" name="title" id="title" size=60 maxlength=255 value="'.esc($thread['title']).'">';
	$renamefield.= '<input type="submit" name="submit" value="Rename" onclick="submitmod(\'rename\')">';
	$renamefield = addcslashes($renamefield, "'"); //because of javascript, single quotes will gum up the works

	$threadtitle = addcslashes(htmlentities($thread['title'], ENT_COMPAT | ENT_HTML401, 'UTF-8'), "'");

	$modlinks = <<<HTML
<br><form action="thread.php?id=$tid" method="post" name="mod" id="mod">
<table class="c1"><tr class="n2">
	<td class="b n2">
		<span id="moptions">Thread options: $stick $close $trash $edit</span>
		<script>
moptions = document.getElementById('moptions');
function showrbox() { moptions.innerHTML = 'Rename thread: $renamefield'; }
function showmove() { moptions.innerHTML = 'Move to: $fmovelinks'; }
		</script>
		<input type=hidden id="arg" name="arg" value="">
		<input type=hidden id="action" name="action" value="">
	</td>
</table>
</form>
HTML;
}

$twig = _twigloader();
echo $twig->render('thread.twig', [
	'thread' => $thread,
	'posts' => $posts,
	'topbot' => $topbot,
	'uid' => $uid ?? null,
	'time' => $time ?? null,
	'modlinks' => $modlinks,
	'pagelist' => $pagelist,
	'faccess' => $faccess ?? null,
	'pin' => $_GET['pin'] ?? null,
	'tid' => $tid ?? null,
	'title' => $title
]);
