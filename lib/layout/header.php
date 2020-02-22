<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Principia - Home</title>
		<link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
	</head>
	<body>
		<div class="top">
			<a href="./"><img class="picon" src="assets/icon.png"/></a>
			<ul class="menu left">
				<li><a href="selected.php" class="btn">Selected</a></li>
				<li><a href="top.php" class="btn">Top</a></li>
				<li><a href="latest.php" class="btn">New</a></li>
				<li><a href="chat.php" class="btn">Chat</a></li>
			</ul>
			<ul class="menu right">
				<li><em><?=($log ? userlink($userdata) : '<a href="login.php">Login</a>') ?></em></li>
			</ul>
		</div>
		<div class="home content">