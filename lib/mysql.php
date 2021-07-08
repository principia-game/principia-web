<?php

$options = [
	PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES		=> false,
];
try {
	$sql = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
} catch (\PDOException $e) {
	die("Error - Can't connect to database. Please try again later.");
}

function query($query,$params = []) {
	global $sql;

	$res = $sql->prepare($query);
	$res->execute($params);
	return $res;
}

function fetch($query,$params = []) {
	$res = query($query,$params);
	return $res->fetch();
}

function result($query,$params = []) {
	$res = query($query,$params);
	return $res->fetchColumn();
}

function fetchArray($query) {
	$out = [];
	while ($record = $query->fetch()) {
		$out[] = $record;
	}
	return $out;
}

function insertId() {
	global $sql;
	return $sql->lastInsertId();
}
