<?php

if (isset($path[2])) {
	if ($path[2] == 'android') {
		$imguiTbl = "imgui_android";
		$template = "imgui_android.twig";
	} else
		error('404');
} else {
	$imguiTbl = "imgui";
	$template = "imgui.twig";
}

$dialogs = query("SELECT * FROM $imguiTbl ORDER BY status DESC, added_in DESC, name ASC");

$numDialogs = result("SELECT COUNT(*) FROM $imguiTbl");
$dialogStatusCounts = query("SELECT status, COUNT(*) AS count FROM $imguiTbl GROUP BY status ORDER BY status DESC");

twigloader()->display($template, [
	"dialogs" => $dialogs,
	"num_dialogs" => $numDialogs,
	"dialog_status_counts" => $dialogStatusCounts
]);
