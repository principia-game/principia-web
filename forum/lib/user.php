<?php

function userfields($tbl = '', $pf = '') {
	$fields = ['id', 'name', 'customcolor'];

	$ret = '';
	foreach ($fields as $f) {
		if ($ret)
			$ret .= ',';
		if ($tbl)
			$ret .= $tbl . '.';
		$ret .= $f;
		if ($pf)
			$ret .= ' ' . $pf . $f;
	}

	return $ret;
}

function userfields_post() {
	$ufields = ['posts','joined','lastpost','lastview','title','avatar','signature'];
	$fieldlist = '';
	foreach ($ufields as $field)
		$fieldlist .= "u.$field u$field,";
	return $fieldlist;
}
