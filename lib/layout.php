<?php

if (DEBUG)
	$profile = new \Twig\Profiler\Profile();

/**
 * Twig loader, initializes Twig with standard configurations and extensions.
 *
 * @param string $subfolder Subdirectory to use in the templates/ directory.
 * @return \Twig\Environment Twig object.
 */
function twigloader() {
	global $log, $footerlinks, $path, $submodule, $profile;

	$loader = new \Twig\Loader\FilesystemLoader('templates/' . (IS_ARCHIVE ? 'archive/' : ''));

	$twig = new \Twig\Environment($loader, [
		'cache' => IS_ARCHIVE ? '/tmp/pwa_cache' : TPL_CACHE,
		'auto_reload' => true,
	]);

	// Add principia-web specific extension
	$twig->addExtension(new PrincipiaExtension());

	if (DEBUG)
		$twig->addExtension(new \Twig\Extension\ProfilerExtension($profile));

	$twig->addGlobal('userdata', $GLOBALS['userdata']);
	$twig->addGlobal('log', $log);
	$twig->addGlobal('glob_lpp', LPP);
	$twig->addGlobal('footerlinks', $footerlinks);
	$twig->addGlobal('domain', DOMAIN);
	$twig->addGlobal('uri', $_SERVER['REQUEST_URI'] ?? null);
	if ($submodule == 'forum')
		$twig->addGlobal('pagename', '/forum/'.($path[2] ?? ''));
	else
		$twig->addGlobal('pagename', '/'.($path[1] ?? ''));

	return $twig;
}

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
	global $submodule;

	if ($title >= 400 && $title < 500) http_response_code($title);

	if ($submodule == 'forum')
		$twig = twigloaderForum();
	else
		$twig = twigloader();

	if (!$message) {
		// Placeholder messages if there is no message.
		$message = match ($title) {
			'403' => "You do not have access to this page or action.",
			'404' => "The requested page was not found. The link may be broken, the page may have been deleted, or you may not have access to it."
		};
	}

	echo $twig->render('_error.twig', ['err_title' => $title, 'err_message' => $message]);
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


function androidWebviewVersion() {
	global $useragent;
	preg_match('/Principia WebView\/([0-9]+) \(Android\)/', $useragent, $matches);

	if (isset($matches[1]))
		return (int)$matches[1];
	else
		return null;
}
