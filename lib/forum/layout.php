<?php

function newStatus($type) {
	$text = match ($type) {
		'n'  => 'NEW',
		'o', 'on' => 'OFF'
	};
	$statusimg = match ($type) {
		'n'  => 'new.png',
		'o'  => 'off.png',
		'on' => 'offnew.png'
	};

	return "<img src=\"/assets/status/$statusimg\" alt=\"$text\">";
}

function renderActions($actions) {
	$out = [];

	foreach ($actions as $url => $title)
		$out[] = ($url == 'none' ? $title : sprintf('<a href="%s">%s</a>', esc($url), $title));

	return join(' &ndash; ', $out);
}

function renderPageBar($pagebar) {
	if (empty($pagebar)) return;

	echo '<div class="breadcrumb"><a href="./">Forum</a> &raquo; ';
	if (!empty($pagebar['breadcrumb'])) {
		foreach ($pagebar['breadcrumb'] as $url => $title)
			printf('<a href="%s">%s</a> &raquo; ', esc($url), $title);
	}
	echo esc($pagebar['title']).'<div class="actions">';
	if (!empty($pagebar['actions']))
		echo renderActions($pagebar['actions']);
	echo "</div></div>";
}

function forumlist($currentforum = -1) {
	global $userdata;
	$r = query("SELECT c.title ctitle,f.id,f.title,f.cat FROM z_forums f LEFT JOIN z_categories c ON c.id=f.cat WHERE ? >= f.minread ORDER BY c.ord,c.id,f.ord,f.id",
		[$userdata['rank']]);
	$out = '<select id="forumselect">';
	$c = -1;
	while ($d = $r->fetch()) {
		if ($d['cat'] != $c) {
			if ($c != -1)
				$out .= '</optgroup>';
			$c = $d['cat'];
			$out .= '<optgroup label="'.$d['ctitle'].'">';
		}
		$out .= sprintf(
			'<option value="%s"%s>%s</option>',
		$d['id'], ($d['id'] == $currentforum ? ' selected="selected"' : ''), $d['title']);
	}
	$out .= "</optgroup></select>";

	return $out;
}

function ifEmptyQuery($message, $colspan = 0, $table = false) {
	if ($table) echo '<table class="c1">';
	echo '<tr><td class="n1 center" '.($colspan != 0 ? "colspan=$colspan" : '')."><p>$message</p></td></tr>";
	if ($table) echo '</table>';
}

function twigloaderForum() {
	$twig = twigloader();

	$twig->addExtension(new PrincipiaForumExtension());

	$twig->addGlobal('submodule', 'forum');

	return $twig;
}

class PrincipiaForumExtension extends \Twig\Extension\AbstractExtension {
	public function getFunctions() {
		return [
			// datetime.php
			new \Twig\TwigFunction('timelinks', 'timelinks', ['is_safe' => ['html']]),

			// layout.php
			new \Twig\TwigFunction('new_status', 'newStatus', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('render_page_bar', 'renderPageBar', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('if_empty_query', 'ifEmptyQuery', ['is_safe' => ['html']]),

			// post.php
			new \Twig\TwigFunction('threadpost', 'threadpost', ['is_safe' => ['html']]),
			new \Twig\TwigFunction('minipost', 'minipost', ['is_safe' => ['html']]),
		];
	}
}

function timelinks($file, $seltime) {
	$relativeTime = new RelativeTime([
		'suffix' => false,
		'truncate' => 1,
	]);

	$links = [];
	foreach ([3600, 86400, 604800, 2592000] as $time) {
		$timelbl = $relativeTime->convert(1, $time+1);

		if ($time == $seltime)
			$links[] = $timelbl;
		else
			$links[] = sprintf('<a href="%s?time=%s">%s</a>', $file, $time, $timelbl);
	}

	return join(' &ndash; ', $links);
}
