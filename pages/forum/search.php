<?php
$query = trim($_GET['query'] ?? '');
$where = $_GET['where'] ?? 0;

$userfields = userfields('u', 'u');
if ($query && $where == 1) {
	// Search by post text (list threadposts)

	$fieldlist = userfields_post();
	$posts = query("SELECT $userfields, $fieldlist, p.*, pt.text, pt.date ptdate, pt.revision cur_revision, t.id tid, t.title ttitle, t.forum tforum
			FROM z_posts p
			JOIN z_poststext pt ON p.id = pt.id AND p.revision = pt.revision
			JOIN users u ON p.user = u.id
			JOIN z_threads t ON p.thread = t.id
			JOIN z_forums f ON f.id = t.forum
			WHERE pt.text LIKE CONCAT('%', ?, '%') AND ? >= f.minread
			ORDER BY p.id DESC LIMIT 20",
		[$query, $userdata['rank']]);

} elseif ($query) {
	// Search by thread title (list threads)

	$threads = query("SELECT $userfields, t.*
		FROM z_threads t
		JOIN users u ON u.id = t.user
		JOIN z_forums f ON f.id = t.forum
		WHERE t.title LIKE CONCAT('%', ?, '%') AND ? >= f.minread
		ORDER BY t.lastdate DESC",
	[$query, $userdata['rank']]);
}

echo _twigloader()->render('search.twig', [
	'query' => $query,
	'where' => $where,
	'threads' => $threads ?? null,
	'posts' => $posts ?? null
]);
