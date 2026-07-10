<?php

$dialogs = query("SELECT * FROM imgui ORDER BY status DESC, name ASC");

$numDialogs = result("SELECT COUNT(*) FROM imgui");
$dialogStatusCounts = query("SELECT status, COUNT(*) AS count FROM imgui GROUP BY status ORDER BY status DESC");

twigloader()->display("imgui.twig", [
	"dialogs" => $dialogs,
	"num_dialogs" => $numDialogs,
	"dialog_status_counts" => $dialogStatusCounts
]);
