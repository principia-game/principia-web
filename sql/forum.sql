-- Adminer 4.8.1 MySQL 10.7.3-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `z_categories` (
  `id` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `ord` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_categories` (`id`, `title`, `ord`) VALUES
(1,	'General',	50),
(2,	'Staff forums',	0);

CREATE TABLE `z_forums` (
  `id` int(5) unsigned NOT NULL DEFAULT 0,
  `cat` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `ord` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `descr` varchar(255) NOT NULL,
  `threads` int(10) unsigned NOT NULL DEFAULT 0,
  `posts` int(10) unsigned NOT NULL DEFAULT 0,
  `lastdate` int(10) unsigned NOT NULL DEFAULT 0,
  `lastuser` int(10) unsigned NOT NULL DEFAULT 0,
  `lastid` int(10) unsigned NOT NULL DEFAULT 0,
  `minread` tinyint(4) NOT NULL DEFAULT -1,
  `minthread` tinyint(4) NOT NULL DEFAULT 1,
  `minreply` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `z_forums` (`id`, `cat`, `ord`, `title`, `descr`, `threads`, `posts`, `lastdate`, `lastuser`, `lastid`, `minread`, `minthread`, `minreply`) VALUES
(1,	1,	0,	'Example forum',	'This is an example forum to get started with.',	0,	0,	0,	0,	0,	-1, 1,  1),
(2,	2,	0,	'Example staff forum',	'This is an example staff forum to get started with.',	0,	0,	0,	0,	0,	2,	2,  2);

CREATE TABLE `z_forumsread` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(5) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_pmsgs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` int(11) unsigned NOT NULL DEFAULT 0,
  `userto` int(10) unsigned NOT NULL,
  `userfrom` int(10) unsigned NOT NULL,
  `unread` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `del_from` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `del_to` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `thread` int(10) unsigned NOT NULL DEFAULT 0,
  `date` int(10) unsigned NOT NULL DEFAULT 0,
  `revision` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `threadid` (`thread`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_poststext` (
  `id` int(11) unsigned NOT NULL DEFAULT 0,
  `text` text NOT NULL,
  `revision` smallint(5) unsigned NOT NULL DEFAULT 1,
  `date` int(11) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`,`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `posts` int(10) unsigned NOT NULL DEFAULT 1,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `forum` int(10) unsigned NOT NULL DEFAULT 0,
  `user` int(10) unsigned NOT NULL DEFAULT 0,
  `lastdate` int(10) unsigned NOT NULL DEFAULT 0,
  `lastuser` int(10) unsigned NOT NULL DEFAULT 0,
  `lastid` int(10) unsigned NOT NULL DEFAULT 0,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `closed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `z_threadsread` (
  `uid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 2022-04-19 12:27:41
