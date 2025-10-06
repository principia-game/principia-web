<?php

const LPP = 20; // Levels per page
const PPP = 20; // Forum posts per page
const TPP = 50; // Forum threads per page

const COOKIE_NAME = '_PRINCSECURITY';

const TRASH_FORUM_ID = 7;

const GETTINGSTARTED_LINKS = [
	'https://principia-web.se/wiki/' => 'Principia Wiki',
	'https://principia-web.se/wiki/Tutorials' => 'Tutorials',
	'https://principia-web.se/wiki/Objects' => 'Wiki Objects List',
	'https://principia-web.se/wiki/LuaScript' => 'Principia Lua Scripting',
	'https://principia-web.se/browse' => 'Browse community levels',
	'https://youtube.com/@principiagame' => 'Principia on YouTube',
	'https://principia-web.se/donate' => 'Donate',
];

const LATEST_VERSION_CODE = 39;

// technically not a constant but let's just say it is

$footerlinks = [
	'/about' => "About",
	'/browse' => "Browse",
	'/wiki/FAQ' => 'FAQ',
	'/rules' => 'Rules',
	'/privacy' => 'Privacy Policy'
];

if (isset($path[1]) && $path[1] != 'report')
	$footerlinks['/report?url='.$_SERVER['REQUEST_URI']] = 'Report';

$footerlinks['/userlist'] = 'User list';
