<?php

const LPP = 20; // Levels per page
const PPP = 20; // Forum posts per page
const TPP = 50; // Forum threads per page

const COOKIE_NAME = '_PRINCSECURITY';

const TRASH_FORUM_ID = 7;

const GETTINGSTARTED_LINKS = [
	'https://principia-web.se/wiki/' => 'Principia Wiki',
	'https://principia-web.se/wiki/Tutorials' => 'Tutorial',
	'https://principia-web.se/wiki/Objects' => 'Wiki Objects List',
	'https://principia-web.se/wiki/LuaScript' => 'Principia Lua Scripting',
	'https://principia-web.se/donate' => 'Donate',
];

// technically not a constant but let's just say it is

if (IS_ARCHIVE) {
	$footerlinks = [
		'https://principia-web.se' => 'Go back'
	];
} else {
	$footerlinks = [
		'/about' => "About",
		'/browse' => "Browse",
		'/wiki/FAQ' => 'FAQ',
		'/rules' => 'Rules',
		'/privacy' => 'Privacy Policy'
	];
}

if (isset($path[1]) && $path[1] != 'report')
	$footerlinks['/report?url='.$_SERVER['REQUEST_URI']] = 'Report';

$footerlinks['/userlist'] = 'User list';
