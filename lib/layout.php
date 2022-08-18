<?php

/**
 * Twig loader, initializes Twig with standard configurations and extensions.
 *
 * @param string $subfolder Subdirectory to use in the templates/ directory.
 * @return \Twig\Environment Twig object.
 */
function twigloader($subfolder = '', $customloader = null, $customenv = null) {
	global $tplCache, $tplNoCache, $userdata, $notificationCount, $log, $lpp, $forumEnabled, $invite, $domain, $uri;

	$doCache = ($tplNoCache ? false : $tplCache);

	if (!isset($customloader)) {
		$loader = new \Twig\Loader\FilesystemLoader('templates/' . $subfolder);
	} else {
		$loader = $customloader();
	}

	if (!isset($customenv)) {
		$twig = new \Twig\Environment($loader, [
			'cache' => $doCache,
		]);
	} else {
		$twig = $customenv($loader, $doCache);
	}

	// Add principia-web specific extension
	$twig->addExtension(new PrincipiaExtension());

	$twig->addGlobal('userdata', $userdata);
	$twig->addGlobal('notification_count', $notificationCount);
	$twig->addGlobal('log', $log);
	$twig->addGlobal('glob_lpp', $lpp);
	$twig->addGlobal('glob_forum', $forumEnabled);
	$twig->addGlobal('discord_invite', $invite);
	$twig->addGlobal('domain', $domain);
	$twig->addGlobal('uri', $uri);

	return $twig;
}

function comments($cmnts, $type, $id) {
	$twig = twigloader('components');
	return $twig->render('comment.twig', ['cmnts' => $cmnts, 'type' => $type, 'id' => $id]);
}

function pagination($levels, $lpp, $url, $current) {
	$twig = twigloader('components');
	return $twig->render('pagination.twig', ['levels' => $levels, 'lpp' => $lpp, 'url' => $url, 'current' => $current]);
}

function error($title, $message) {
	global $acmlm;

	if ($acmlm)
		$twig = _twigloader();
	else
		$twig = twigloader();

	echo $twig->render('_error.twig', ['err_title' => $title, 'err_message' => $message]);
	die();
}

function level($level, $featured = '', $pkg = false) {
	global $cache;
	return $cache->hit($level, function () use ($level, $featured, $pkg) {
		$twig = twigloader('components');

		if (!$pkg) {
			if (!isset($level['visibility']) || $level['visibility'] != 1) {
				$img = "levels/thumbs/low/".$level['id'].".jpg";
			} else {
				$img = "assets/locked_thumb.svg";
			}
		} else {
			$img = "assets/package_thumb.svg";
		}

		if ($pkg) {
			$page = 'package.php';
		} else {
			$page = 'level.php';
		}

		return $twig->render('level.twig', ['level' => $level, 'featured' => $featured, 'img' => $img, 'page' => $page]);
	});
}

function relativeTime($time) {
	$relativeTime = new \RelativeTime\RelativeTime([
		'language' => '\RelativeTime\Languages\English',
		'separator' => ', ',
		'suffix' => true,
		'truncate' => 1,
	]);

	return $relativeTime->timeAgo($time);
}

function redirect($url) {
	header(sprintf('Location: %s', $url));
	die();
}

/**
 * Is the useragent Principia's android webview useragent?
 */
function isAndroidWebview() {
	global $useragent;
	return str_contains($useragent, 'Principia WebView');
}
