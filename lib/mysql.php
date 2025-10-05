<?php

$sql = null;

function connectSql() {
	global $sql;

	try {
		$sql = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, [
			PDO::ATTR_ERRMODE				=> PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE	=> PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES		=> false,
		]);
	} catch (\PDOException $e) {
		http_response_code(500);
		die("Error - Can't connect to database. Please try again later.");
	}
}

function query($query, $params = [], $placeholders = []) {
	global $sql;

	if (!$sql) connectSql();

	$query = str_replace("@userfields", userfields(), $query);

	$archivePrefix = IS_ARCHIVE ? 'archive_' : '';
	$query = str_replace('@levels', $archivePrefix.'levels', $query);
	$query = str_replace('@users', $archivePrefix.'users', $query);

	if (!empty($placeholders))
		$query = vsprintf($query, $placeholders);

	$res = $sql->prepare($query);
	$res->execute($params);
	return $res;
}

function fetch($query, $params = [], $placeholders = []) {
	$res = query($query, $params, $placeholders);
	return $res->fetch();
}

function result($query, $params = [], $placeholders = []) {
	$res = query($query, $params, $placeholders);
	return $res->fetchColumn();
}

function fetchArray($query) {
	$out = [];
	while ($record = $query->fetch())
		$out[] = $record;

	return $out;
}

function insertId() {
	global $sql;
	return $sql->lastInsertId();
}

/**
 * Helper function to insert a row into a table.
 */
function insertInto($table, $data, $dry = false) {
	$fields = [];
	$placeholders = [];
	$values = [];

	foreach ($data as $field => $value) {
		$fields[] = $field;
		$placeholders[] = '?';
		$values[] = $value;
	}

	$query = sprintf(
		"INSERT INTO %s (%s) VALUES (%s)",
	$table, commasep($fields), commasep($placeholders));

	if ($dry)
		return $query;
	else
		return query($query, $values);
}

/**
 * Helper function to construct part of a query to set a lot of fields in one row
 */
function updateRowQuery($fields) {
	// Temp variables for dynamic query construction.
	$fieldquery = '';
	$placeholders = [];

	// Construct a query containing all fields.
	foreach ($fields as $fieldk => $fieldv) {
		if ($fieldquery) $fieldquery .= ',';
		$fieldquery .= $fieldk.'=?';
		$placeholders[] = $fieldv;
	}

	return ['fieldquery' => $fieldquery, 'placeholders' => $placeholders];
}

function paginate($page, $pp) {
	$page = (is_numeric($page) && $page > 0 ? $page : 1);

	return sprintf(" LIMIT %s, %s", (($page - 1) * $pp), $pp);
}
