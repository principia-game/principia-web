<?php

function postfilter($msg) {
	$markdown = new Parsedown();
	$markdown->setSafeMode(true);
	$msg = $markdown->text($msg);

	$msg = preg_replace("'\[reply=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=showprivate.php?id=\\2>Sent by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/reply]', '<hr></blockquote>', $msg);
	$msg = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=thread.php?pid=\\2#\\2>Posted by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/quote]', '<hr></blockquote>', $msg);

	return $msg;
}

function esc($text) {
	return htmlspecialchars($text);
}

function threadpost($post, $pthread = '') {
	global $log, $userdata;

	if (isset($post['deleted']) && $post['deleted']) {
		if ($userdata['powerlevel'] > 1) {
			$postlinks = sprintf(
				'<a href="thread.php?pid=%s&pin=%s#%s">Peek</a> &bull; <a href="editpost.php?pid=%s&act=undelete">Undelete</a> &bull; ID: %s',
			$post['id'], $post['id'], $post['id'], $post['id'], $post['id']);
		} else {
			$postlinks = 'ID: '.$post['id'];
		}

		$ulink = userlink($post, 'u');
		return <<<HTML
<table class="c1 threadpost"><tr>
	<td class="b n1 topbar_1">$ulink</td>
	<td class="b n1 topbar_2 fullwidth">(post deleted) <span class="float-right">$postlinks</span></td>
</tr></table>
HTML;
	}

	$headerbar = $threadlink = $postlinks = $revisionstr = '';

	if (isset($post['headerbar'])) {
		$headerbar = sprintf('<tr class="h"><td class="b h" colspan="2">%s</td></tr>', $post['headerbar']);
	}

	$post['utitle'] = $post['utitle'] . ($post['utitle'] ? '<br>' : '');

	$post['id'] = $post['id'] ?? 0;

	if ($pthread)
		$threadlink = sprintf(' - in <a href="thread.php?id=%s">%s</a>', $pthread['id'], esc($pthread['title']));

	if ($post['id'])
		$postlinks = "<a href=\"thread.php?pid=$post[id]#$post[id]\">Link</a>";

	if (isset($post['revision']) && $post['revision'] >= 2)
		$revisionstr = " (edited ".date('Y-m-d H:i', $post['ptdate']).")";

	if (isset($post['thread']) && $log) {
		// TODO: check minreply
		$postlinks .= " &bull; <a href=\"newreply.php?id=$post[thread]&pid=$post[id]\">Quote</a>";

		// "Edit" link for admins or post owners, but not banned users
		if ($userdata['powerlevel'] > 2 || $userdata['id'] == $post['uid'])
			$postlinks .= " &bull; <a href=\"editpost.php?pid=$post[id]\">Edit</a>";

		if ($userdata['powerlevel'] > 1)
			$postlinks .= ' &bull; <a href="editpost.php?pid='.$post['id'].'&act=delete">Delete</a>';

		if (isset($post['maxrevision']) && $userdata['powerlevel'] > 1 && $post['maxrevision'] > 1) {
			$revisionstr .= " &bull; Revision ";
			for ($i = 1; $i <= $post['maxrevision']; $i++)
				$revisionstr .= "<a href=\"thread.php?pid=$post[id]&pin=$post[id]&rev=$i#$post[id]\">$i</a> ";
		}
	}

	if (isset($post['thread']))
		$postlinks .= " &bull; ID: $post[id]";

	$ulink = userlink($post, 'u');
	$pdate = date('Y-m-d H:i', $post['date']);
	$lastpost = ($post['ulastpost'] ? timeunits(time() - $post['ulastpost']) : 'none');
	$lastview = timeunits(time() - $post['ulastview']);
	$picture = ($post['uavatar'] ? "<img src=\"userpic/{$post['uid']}\" alt=\"(Avatar)\">" : '');
	if (!$log) $post['usignature'] = '';
	else if ($post['usignature']) {
		$post['usignature'] = '<div class="siggy">' . postfilter($post['usignature']) . '</div>';
	}
	$ujoined = date('Y-m-d', $post['ujoined']);
	$posttext = postfilter($post['text']);
	return <<<HTML
<table class="c1 threadpost" id="{$post['id']}">
	$headerbar
	<tr>
		<td class="b n1 topbar_1">$ulink</td>
		<td class="b n1 topbar_2 fullwidth">Posted on $pdate$threadlink$revisionstr <span class="float-right">$postlinks</span></td>
	</tr><tr valign="top">
		<td class="b n1 sidebar">
			{$post['utitle']}
			$picture
			<br>Posts: {$post['uposts']}
			<br>
			<br>Since: $ujoined
			<br>
			<br>Last post: $lastpost
			<br>Last view: $lastview
		</td>
		<td class="b n2 mainbar" id="post_{$post['id']}">$posttext{$post['usignature']}</td>
	</tr>
</table>
HTML;
}
