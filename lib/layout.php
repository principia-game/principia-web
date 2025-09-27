<?php

function comments($cmnts, $type, $id, $showheader = true) {
	return twigloader()->render('components/comment.twig', [
		'cmnts' => $cmnts, 'type' => $type, 'id' => $id, 'showheader' => $showheader
	]);
}

function pagination($levels, $pp, $url, $current) {
	return twigloader()->render('components/pagination.twig', [
		'levels' => $levels, 'lpp' => $pp, 'url' => $url, 'current' => $current
	]);
}

function error($title, $message = '') {
	if ($title >= 400 && $title < 500) http_response_code($title);

	if (!$message) {
		// Placeholder messages if there is no message.
		$message = match ($title) {
			'403' => "You do not have access to this page or action.",
			'404' => "The requested page was not found. The link may be broken, the page may have been deleted, or you may not have access to it."
		};
	}

	twigloader()->display('_error.twig', ['err_title' => $title, 'err_message' => $message]);
	die();
}

function level($level, $featured = '', $pkg = false) {
	if (!$pkg) {
		if (!isset($level['visibility']) || $level['visibility'] != 1) {
			if (IS_ARCHIVE)
				$img = "thumbs/low/".$level['id']."-0-0.jpg";
			else
				$img = "thumbs/low/".$level['id'].".jpg";
		} else
			$img = "assets/locked_thumb.svg";
	} else
		$img = "assets/package_thumb.svg";

	$page = ($pkg ? 'package' : 'level');
	$label = $featured ? "<span class=\"featured small\">{$featured}</span>" : '';
	$title = esc(strlen($level['title']) > 60 ? substr($level['title'], 0, 60).'...' : $level['title']);
	$author = userlink($level, 'u_');

	return <<<HTML
<div class="level" id="l-{$level['id']}">
	<a class="lvlbox_top" href="/{$page}/{$level['id']}">
		<img src="/{$img}" alt="" loading="lazy">$label
		<div class="lvltitle">$title</div>
	</a>
	{$author}
</div>
HTML;
}

function relativeTime($time) {
	if (!$time) return 'never';

	$relativeTime = new RelativeTime([
		'truncate' => 1,
	]);

	return $relativeTime->timeAgo($time);
}
