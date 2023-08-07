<?php
$action = $_POST['action'] ?? null;

$pagedata = fetch("SELECT p.*, r.content FROM wikipages p JOIN wikirevisions r ON p.cur_revision = r.revision AND p.title = r.page WHERE BINARY p.title = ?", [$page]);

if ($action == 'Show changes' && $pagedata) {
	$diff = new Diff(
		explode("\n", $pagedata['content']),
		explode("\n", normalise($_POST['text'])));
	$renderer = new Diff_Renderer_Html_Inline;
	$diffoutput = $diff->render($renderer);
}

if ($action == 'Preview' || $action == 'Show changes') {
	$pagedata['content'] = $_POST['text'];
	$description = $_POST['description'];
}

if ($log && $action == 'Save changes' && $userdata['rank'] >= $pagedata['minedit']) {
	$content = normalise($_POST['text'] ?? '');
	$description = $_POST['description'] ?? null;
	$size = strlen($content);

	if ($pagedata) {
		query("UPDATE wikipages SET cur_revision = cur_revision + 1 WHERE BINARY title = ?",
			[$page]);

		$newrev = result("SELECT cur_revision FROM wikipages WHERE BINARY title = ?", [$page]);
		$oldsize = result("SELECT size FROM wikirevisions WHERE BINARY page = ? AND revision = ?", [$page, $newrev-1]);

		query("INSERT INTO wikirevisions (page, revision, author, time, size, sizediff, description, content) VALUES (?,?,?,?,?,?,?,?)",
			[$page, $newrev, $userdata['id'], time(), $size, ($size - $oldsize), $description, $content]);
	} else {
		$cache->delete('wpe_'.base64_encode($page));

		query("INSERT INTO wikipages (title) VALUES (?)",
			[$page]);

		$newrev = 1;

		query("INSERT INTO wikirevisions (page, author, time, size, description, content) VALUES (?,?,?,?,?,?)",
			[$page, $userdata['id'], time(), $size, $description, $content]);
	}

	$minedit = $_POST['minedit'] ?? null;
	if ($userdata['rank'] > 2 && $minedit) {
		query("UPDATE wikipages SET minedit = ? WHERE BINARY title = ?",
			[$minedit, $page]);
	}

	wikiEditHook([
		'page' => $page,
		'page_slugified' => $page_slugified,
		'description' => $description,
		'revision' => $newrev,
		'u_id' => $userdata['id'],
		'u_name' => $userdata['name']
	]);

	redirect("/wiki/$page_slugified");
}

$pagedata['minedit'] = $_POST['minedit'] ?? ($pagedata['minedit'] ?? 1);

echo _twigloader()->render('edit.twig', [
	'pagetitle' => $page,
	'pagetitle_slugified' => str_replace(' ', '_', $page),
	'page' => $pagedata,
	'action' => $action,
	'change_description' => $description ?? null,
	'ranks' => $ranks,
	'diff' => $diffoutput ?? null
]);
