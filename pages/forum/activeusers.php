<?php
$time = (int)($_GET['time'] ?? 86400);

$ufields = userfields('u');
$users = query("SELECT $ufields,u.posts,u.joined,COUNT(*) num FROM users u LEFT JOIN z_posts p ON p.user = u.id WHERE p.date > ? GROUP BY u.id ORDER BY num DESC",
	[(time() - $time)]);

echo twigloaderForum()->render('activeusers.twig', [
	'time' => $time,
	'users' => $users
]);
