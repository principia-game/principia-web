<?php
require("lib/common.php");

needsLogin();

$query = $_GET['q'] ?? '';
$where = $_GET['w'] ?? 0;

ob_start();

?>
<form action="search" method="get"><table class="c1">
	<tr class="h"><td colspan="2">Search Forums</td></tr>

	<tr>
		<td class="n1 center" width="150">Search for:</td>
		<td class="n2"><input type="text" name="q" size="40" value="<?=htmlspecialchars($query, ENT_QUOTES) ?>"></td>
	</tr><tr>
		<td class="n1 center">In:</td>
		<td class="n2">
			<input type="radio" name="w" value="0" id="threadtitle" <?=(($where == 0) ? 'checked' : '') ?>><label for="threadtitle">thread title</label>
			<input type="radio" name="w" value="1" id="posttext" <?=(($where == 1) ? 'checked' : '') ?>><label for="posttext">post text</label>
	</tr><tr class="n1">
		<td></td>
		<td><input type="submit" name="action" value="Search"></td>
	</tr>
</table></form>
<?php
if (!isset($_GET['action']) || strlen($query) < 3) {
	if (isset($_GET['action']) && strlen($query) < 3) {
		echo '<br><table class="c1"><tr><td class="n1 center">Please enter more than 2 characters!</td></tr></table>';
	}
	$content = ob_get_contents();
	ob_end_clean();

	echo _twigloader()->render('_legacy.twig', [
		'page_title' => "Search",
		'content' => $content
	]);

	die();
}

echo '<br><table class="c1"><tr class="h"><td style="border-bottom:0">Results</td></tr></table>';

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
			<td>Title</td>
			<td style="min-width:80px">Started by</td>
			<td width="200">Date</td>
		</tr><?php

	for ($i = 1; $thread = $threads->fetch(); $i++) {
		$tr = ($i % 2 ? 'n2' :'n3');

		?><tr class="<?=$tr ?> center">
			<td class="left wbreak">
				<a href="thread?id=<?=$thread['id'] ?>"><?=esc($thread['title']) ?></a> <?=($thread['sticky'] ? ' (Sticky)' : '')?>
			</td>
			<td><?=userlink($thread,'u') ?></td>
			<td><?=date('Y-m-d H:i',$thread['lastdate']) ?></td>
		</tr><?php
	}
	if ($i == 1)
		ifEmptyQuery("No threads found.", 6);

	$query = urlencode($query);
	echo '</table>'.pagination($threadcount, $tpp, "search?q=$query&action=Search&w=0&page=%s", $page);
}

$content = ob_get_contents();
ob_end_clean();

echo _twigloader()->render('_legacy.twig', [
	'page_title' => "Search",
	'content' => $content
]);
