<?php
require('lib/common.php');
pageheader();

$lid = (isset($_GET['id']) ? $_GET['id'] : 0);

$level = fetch("SELECT l.*, u.id u_id, u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.id = ?", [$lid]);

// TODO: Get platform from database.
// TODO: Implement disabling the edit button (from enabling/disabling derivates).
// TODO: Increment views and downloads.
?>
<h1><?=$level['title'] ?></h1>
<span class="misc">
	  <?=userlink($level, 'u_') ?>
	- <?=ucfirst(cat_to_type($level['cat'])) ?> level
	- <?=date('M j, Y')?> from Android</span>
<div class="lvl-box">
	<div class="info">
		<div id="buttons">
			<a class="play" href="principia://play/lvl/db/<?=$lid ?>">Play</a>
			<a class="play play-edit" href="principia://sandbox/db/<?=$lid ?>">Edit</a>
		</div>
		<p><?=$level['description'] ?></p>
	</div>
	<div class="img">
		<img src="assets/placeholder.png">
	</div>
</div>
<div class="lvl-data">
	<p>
		Views: <?=$level['views'] ?>
		Downloads: <?=$level['downloads'] ?>
		Level ID: <?=$level['id'] ?>
	</p>
</div>
<?php

pagefooter();