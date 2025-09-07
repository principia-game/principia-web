<?php
$users = query("SELECT id, name, customcolor, rank, levels FROM users");

twigloader()->display('userlist.twig', [
	'users' => $users
]);
