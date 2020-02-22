<?php
require('lib/common.php');
pageheader();

$newsdata = query("SELECT * FROM news ORDER BY id DESC LIMIT 5");

$latestquery = "SELECT l.id id,l.title title,u.id u_id,u.name u_name FROM levels l JOIN users u ON l.author = u.id WHERE l.cat = %d ORDER BY l.id DESC LIMIT 5";
$latestcustom = query(sprintf($latestquery, 1));
$latestadvent = query(sprintf($latestquery, 2));

if (isset($_GET['rd'])) echo '<div class="header_message">You\'ve been successfully registered!</div>';
?>
<h2 class="header">Latest news<a href="news.php">More</a></h3>
<ul>
	<?php while ($record = $newsdata->fetch()) { ?>
	<li><a href="news.php?id=<?=$record['id'] ?>"><?=$record['title'] ?></a></li>
	<?php } ?>
</ul>

<h2 class="header">Latest custom levels <a href="latest.php?type=custom">More</a></h2>
<?php while ($record = $latestcustom->fetch()) {
	echo level($record) . ' ';
} ?>

<h2 class="header">Latest adventures <a href="latest.php?type=adventure">More</a></h2>
<?php while ($record = $latestadvent->fetch()) {
	echo level($record) . ' ';
} ?>

<h2 class="header">Latest comments<a href="comments">More</a></h2>
<div class="comments">
<?php for ($i = 1; $i <= 10; $i++) { ?>
<div class="comment-entry" id="comment-1">
	<p>
		<a class="user" href="user.php?id=1"><span class="t_user">null</span></a>
		commented on <a href="level.php?id=1">some level</a>:
		<span class="date">2 hours ago</span>
	</p>
	<span class="comment-text">Blah blah</span>
</div>
<?php } ?>
</div>
<br>
<?php pagefooter() ?>