<?php
require('lib/common.php');
pageheader();

$type = (isset($_GET['type']) ? $_GET['type'] : 'all');
?>
<h2>Latest levels</h2>
<div class="latest-buttons">
	<a <?=($type == 'custom' ? 'class="sel" ' : '') ?>href="latest.php?type=custom">Custom</a>
	<a <?=($type == 'adventure' ? 'class="sel" ' : '') ?>href="latest.php?type=adventure">Adventure</a>
	<a <?=($type == 'puzzle' ? 'class="sel" ' : '') ?>href="latest.php?type=puzzle">Puzzle</a>
	<a <?=($type == 'all' ? 'class="sel" ' : '') ?>href="latest.php">All</a>
</div>
<?php

$where = ($type != 'all' ? "WHERE l.cat = ".type_to_cat($type) : '');

$levels = query("SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id $where ORDER BY l.id DESC");

while ($record = $levels->fetch()) {
	echo level($record) . ' ';
}

echo '<br><br>';
pagefooter();