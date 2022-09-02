<?php
require("lib/common.php");

needsLogin();

$query = $_GET['q'] ?? '';
$where = $_GET['w'] ?? 0;

ob_start();

?>
<table class="c1">
	<tr class="h"><td class="b h">Search</td>
	<tr><td class="b n1">
		<form action="search" method="get"><table>
			<tr>
				<td>Search for</td>
				<td><input type="text" name="q" size="40" value="<?=htmlspecialchars($query, ENT_QUOTES) ?>"></td>
			</tr><tr>
				<td></td>
				<td>
					in <input type="radio" class="radio" name="w" value="0" id="threadtitle" <?=(($where == 0) ? 'checked' : '') ?>><label for="threadtitle">thread title</label>
					<input type="radio" class="radio" name="w" value="1" id="posttext" <?=(($where == 1) ? 'checked' : '') ?>><label for="posttext">post text</label>
					<br><input type="submit" name="action" value="Search">
				</td>
			</tr>
		</table></form>
	</td></tr>
</table>
<?php
if (!isset($_GET['action']) || strlen($query) < 3) {
	if (isset($_GET['action']) && strlen($query) < 3) {
		echo '<br><table class="c1"><tr><td class="b n1 center">Please enter more than 2 characters!</td></tr></table>';
	}
	$content = ob_get_contents();
	ob_end_clean();

	$twig = _twigloader();
	echo $twig->render('_legacy.twig', [
		'page_title' => "Search",
		'content' => $content
	]);

	die();
}

echo '<br><table class="c1"><tr class="h"><td class="b h" style="border-bottom:0">Results</td></tr></table>';

$ufields = userfields('u','u');
if ($where == 1) {
	$fieldlist = userfields_post();
	$posts = query("SELECT $ufields, $fieldlist p.*, pt.text, pt.date ptdate, pt.revision cur_revision, t.id tid, t.title ttitle, t.forum tforum
			FROM z_posts p
			LEFT JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			LEFT JOIN users u ON p.user = u.id
			LEFT JOIN z_threads t ON p.thread = t.id
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE pt.text LIKE CONCAT('%', ?, '%') AND ? >= f.minread
			ORDER BY p.id",
		[$query, $userdata['powerlevel']]);

	for ($i = 1; $post = $posts->fetch(); $i++) {
		$pthread['id'] = $post['tid'];
		$pthread['title'] = $post['ttitle'];
		echo '<br>' . threadpost($post,$pthread);
	}

	if ($i == 1)
		ifEmptyQuery('No posts found.', 1, true);
} else {
	$page = $_GET['page'] ?? 1;
	if ($page < 1) $page = 1;

	$threads = query("SELECT $ufields, t.*
			FROM z_threads t
			LEFT JOIN users u ON u.id = t.user
			LEFT JOIN z_forums f ON f.id = t.forum
			WHERE t.title LIKE CONCAT('%', ?, '%') AND ? >= f.minread
			ORDER BY t.lastdate DESC LIMIT ?,?",
		[$query, $userdata['powerlevel'], ($page - 1) * $tpp, $tpp]);

	$threadcount = result("SELECT COUNT(*) FROM z_threads t
			LEFT JOIN z_forums f ON f.id=t.forum
			WHERE t.title LIKE CONCAT('%', ?, '%') AND ? >= f.minread",
		[$query, $userdata['powerlevel']]);

	?><table class="c1">
		<tr class="c">
			<td class="b h">Title</td>
			<td class="b h" style="min-width:80px">Started by</td>
			<td class="b h" width="200">Date</td>
		</tr><?php

	for ($i = 1; $thread = $threads->fetch(); $i++) {
		$tr = ($i % 2 ? 'n2' :'n3');

		?><tr class="<?=$tr ?> center">
			<td class="b left wbreak">
				<a href="thread?id=<?=$thread['id'] ?>"><?=esc($thread['title']) ?></a> <?=($thread['sticky'] ? ' (Sticky)' : '')?>
			</td>
			<td class="b"><?=userlink($thread,'u') ?></td>
			<td class="b"><?=date('Y-m-d H:i',$thread['lastdate']) ?></td>
		</tr><?php
	}
	if ($i == 1)
		ifEmptyQuery("No threads found.", 6);

	$query = urlencode($query);
	echo '</table>'.pagelist($threadcount, $tpp, "search?q=$query&action=Search&w=0", $page);
}

$content = ob_get_contents();
ob_end_clean();

$twig = _twigloader();
echo $twig->render('_legacy.twig', [
	'page_title' => "Search",
	'content' => $content
]);
