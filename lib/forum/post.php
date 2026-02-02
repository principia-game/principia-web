<?php

function postfilter($msg) {
	$msg = str_replace("[/quote]", "[/quote]\n\n", $msg);

	$msg = markdown($msg);

	$msg = preg_replace("'\[reply=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=showprivate?id=\\2>Sent by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/reply]', '<hr></blockquote>', $msg);
	$msg = preg_replace("'\[quote=\"(.*?)\" id=\"(.*?)\"\]'si", '<blockquote><span class="quotedby"><small><i><a href=thread?pid=\\2#\\2>Posted by \\1</a></i></small></span><hr>', $msg);
	$msg = str_replace('[/quote]', '<hr></blockquote>', $msg);

	return $msg;
}

function minipost($post) {
	if (isset($post['deleted']) && $post['deleted']) return;

	$ulink = userlink($post, 'u');
	$pdate = date('Y-m-d H:i', $post['date']);

	$posttext = postfilter($post['text']);

	return <<<HTML
		<tr>
			<td class="n2 topbar_mobile blkm nod clearfix sep_mini">
				$ulink
			</td>
		</tr>
		<tr>
			<td class="n2 sidebar nom sep_mini" rowspan="2">
				$ulink
				<br>
				<br>Posts: {$post['uposts']}
			</td>
			<td class="n2 topbar blkm sep_mini">Posted on $pdate
				<span class="float-right"><a href="thread?pid={$post['id']}#{$post['id']}">Link</a> &ndash; ID: {$post['id']}</span>
			</td>
		</tr><tr>
			<td class="n2 mainbar">$posttext</td>
		</tr>
	HTML;
}

function threadpost($post, $pthread = '') {
	global $log, $userdata;

	if (isset($post['deleted']) && $post['deleted']) {
		if (!IS_MOD) return;

		$pid = $post['id'];
		$ulink = userlink($post, 'u');
		return <<<HTML
			<table class="c1 threadpost" id="{$post['id']}"><tr>
				<td class="n1 sidebar">$ulink</td>
				<td class="n1 topbar">
					(post deleted)
					<span class="float-right">
						<a href="thread?pid=$pid&pin=$pid#$pid">Peek</a>
						&ndash; <a href="editpost?pid=$pid&act=undelete">Undelete</a>
					</span>
				</td>
			</tr></table>
		HTML;
	}

	$headerbar = $threadlink = $postlinks = $revisionstr = '';

	if (isset($post['headerbar']))
		$headerbar = sprintf('<tr class="h"><td colspan="2">%s</td></tr>', $post['headerbar']);

	$post['id'] = $post['id'] ?? 0;
	$postlinks = [];

	if ($pthread)
		$threadlink = sprintf(' - in <a href="thread?id=%s">%s</a>', $pthread['id'], esc($pthread['title']));

	if ($post['id'])
		$postlinks[] = "<a href=\"thread?pid=$post[id]#$post[id]\">Link</a>";

	if (isset($post['revision']) && $post['revision'] >= 2)
		$revisionstr = " (edited ".date('Y-m-d H:i', $post['ptdate']).")";

	if (isset($post['thread']) && $log) {
		// TODO: check minreply
		$postlinks[] = "<a href=\"newreply?id=$post[thread]&pid=$post[id]\">Quote</a>";

		// "Edit" link for admins or post owners, but not banned users
		if (IS_ADMIN || $userdata['id'] == $post['uid'])
			$postlinks[] = '<a href="editpost?pid='.$post['id'].'">Edit</a>';

		if (IS_MOD)
			$postlinks[] = '<a href="editpost?pid='.$post['id'].'&act=delete">Delete</a>';

		if (isset($post['maxrevision']) && IS_MOD && $post['maxrevision'] > 1) {
			$revisionstr .= " &ndash; Revision ";
			for ($i = 1; $i <= $post['maxrevision']; $i++)
				$revisionstr .= "<a href=\"thread?pid=$post[id]&pin=$post[id]&rev=$i#$post[id]\">$i</a> ";
		}
	}

	$postlinks = join(' &ndash; ', $postlinks);

	$ulink = userlink($post, 'u');
	$pdate = date('Y-m-d H:i', $post['date']);
	$picture = ($post['uavatar'] ? '<img class="avatar" src="'.avatarUrl($post, 'u').'" alt="(Avatar)"><br>' : '');

	$signature = $post['usignature'] && $log ? '<div class="siggy">'.postfilter($post['usignature']).'</div>' : '';

	$ujoined = date('Y-m-d', $post['ujoined']);
	$posttext = postfilter($post['text']);
	return <<<HTML
		<table class="c1 threadpost" id="{$post['id']}">
			$headerbar
			<tr>
				<td class="n2 topbar_mobile blkm nod clearfix">
					<span style="float:left;margin-right:10px">$picture</span>
					$ulink
				</td>
			</tr>
			<tr>
				<td class="n2 sidebar nom" rowspan="2">
					$picture
					$ulink
					<br>
					<br><strong>Posts:</strong> {$post['uposts']}
					<br><strong>Joined:</strong> $ujoined
				</td>
				<td class="n2 topbar blkm clearfix">
					Posted on $pdate$threadlink$revisionstr <span class="float-right">$postlinks</span>
				</td>
			</tr><tr>
				<td class="n2 mainbar">$posttext$signature</td>
			</tr>
		</table>
	HTML;
}
