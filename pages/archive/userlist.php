<?php
$users = query("SELECT id, name, customcolor, rank, levels FROM users");

twigloader()->display('archive/userlist.twig', [
	'users' => $users
]);
