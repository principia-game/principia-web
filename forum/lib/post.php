<?php

function postfilter($msg) {
	$markdown = new Parsedown();
	$markdown->setSafeMode(true);
	$msg = $markdown->text($msg);

	$msg = preg_replace("'\[reply=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=showprivate?id=\\2>Sent by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/reply]', '<hr></blockquote>', $msg);
	$msg = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=thread?pid=\\2#\\2>Posted by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/quote]', '<hr></blockquote>', $msg);

	return $msg;
}

function esc($text) {
	return htmlspecialchars($text);
}

function minipost($post) {
	if (isset($post['deleted']) && $post['deleted']) return;

	$ulink = userlink($post, 'u');
	$pdate = date('Y-m-d H:i', $post['date']);

	$posttext = postfilter($post['text']);

	return <<<HTML
	<tr>
		<td class="n1 topbar_1 nom">$ulink</td>
		<td class="n1 topbar_1 blkm nod clearfix">$ulink</td>
		<td class="n1 topbar_2 sfont blkm">Posted on $pdate
			<span class="float-right"><a href="thread.php?pid={$post['id']}#{$post['id']}">Link</a> &bull; ID: {$post['id']}</span></td>
	</tr><tr valign="top">
		<td class="n1 sfont sidebar nom">
			Posts: {$post['uposts']}
		</td>
		<td class="n2 mainbar">$posttext</td>
	</tr>
HTML;
}

function threadpost($post, $pthread = '') {
	global $log, $userdata;

	if (isset($post['deleted']) && $post['deleted']) {
		if ($userdata['rank'] > 1) {
			$postlinks = sprintf(
				'<a href="thread?pid=%s&pin=%s#%s">Peek</a> &bull; <a href="editpost?pid=%s&act=undelete">Undelete</a> &bull; ID: %s',
			$post['id'], $post['id'], $post['id'], $post['id'], $post['id']);
		} else {
			$postlinks = 'ID: '.$post['id'];
		}

		$ulink = userlink($post, 'u');
		return <<<HTML
<table class="c1 threadpost" id="{$post['id']}"><tr>
	<td class="n1 topbar_1">$ulink</td>
	<td class="n1 topbar_2 fullwidth">(post deleted) <span class="float-right">$postlinks</span></td>
</tr></table>
HTML;
	}

	$headerbar = $threadlink = $postlinks = $revisionstr = '';

	if (isset($post['headerbar']))
		$headerbar = sprintf('<tr class="h"><td colspan="2">%s</td></tr>', $post['headerbar']);

	$post['id'] = $post['id'] ?? 0;

	if ($pthread)
		$threadlink = sprintf(' - in <a href="thread?id=%s">%s</a>', $pthread['id'], esc($pthread['title']));

	if ($post['id'])
		$postlinks = "<a href=\"thread?pid=$post[id]#$post[id]\">Link</a>";

	if (isset($post['revision']) && $post['revision'] >= 2)
		$revisionstr = " (edited ".date('Y-m-d H:i', $post['ptdate']).")";

	if (isset($post['thread']) && $log) {
		// TODO: check minreply
		$postlinks .= " &bull; <a href=\"newreply?id=$post[thread]&pid=$post[id]\">Quote</a>";

		// "Edit" link for admins or post owners, but not banned users
		if ($userdata['rank'] > 2 || $userdata['id'] == $post['uid'])
			$postlinks .= " &bull; <a href=\"editpost?pid=$post[id]\">Edit</a>";

		if ($userdata['rank'] > 1)
			$postlinks .= ' &bull; <a href="editpost?pid='.$post['id'].'&act=delete">Delete</a>';

		if (isset($post['maxrevision']) && $userdata['rank'] > 1 && $post['maxrevision'] > 1) {
			$revisionstr .= " &bull; Revision ";
			for ($i = 1; $i <= $post['maxrevision']; $i++)
				$revisionstr .= "<a href=\"thread?pid=$post[id]&pin=$post[id]&rev=$i#$post[id]\">$i</a> ";
		}
	}

	if (isset($post['thread']))
		$postlinks .= " &bull; ID: $post[id]";

	$ulink = userlink($post, 'u');
	$pdate = date('Y-m-d H:i', $post['date']);
	$lastpost = relativeTime($post['ulastpost']);
	$lastview = relativeTime($post['ulastview']);
	$picture = ($post['uavatar'] ? "<img src=\"/userpic/{$post['uid']}\" alt=\"(Avatar)\">" : '');

	$signature = $post['usignature'] && $log ? '<div class="siggy">'.postfilter($post['usignature']).'</div>' : '';

	$ujoined = date('Y-m-d', $post['ujoined']);
	$posttext = postfilter($post['text']);
	return <<<HTML
<table class="c1 threadpost" id="{$post['id']}">
	$headerbar
	<tr>
		<td class="n1 topbar_1 nom">$ulink</td>
		<td class="n1 topbar_1 blkm nod clearfix">
			<span style="float:left;margin-right:10px">$picture</span>
			$ulink
		</td>
		<td class="n1 topbar_2 blkm clearfix">Posted on $pdate$threadlink$revisionstr <span class="float-right">$postlinks</span></td>
	</tr><tr valign="top">
		<td class="n1 sidebar nom">
			$picture
			<br>Posts: {$post['uposts']}
			<br>
			<br>Joined: $ujoined
			<br>
			<br>Last post: $lastpost
			<br>Last view: $lastview
		</td>
		<td class="n2 mainbar">$posttext$signature</td>
	</tr>
</table>
HTML;
}
