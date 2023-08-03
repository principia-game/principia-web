-- Adminer 4.8.1 MySQL 10.11.2-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `z_categories` (
  `id` tinyint(3) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `ord` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_forums` (
  `id` int(10) unsigned NOT NULL DEFAULT 0,
  `cat` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `ord` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL,
  `descr` varchar(200) NOT NULL,
  `threads` int(10) unsigned NOT NULL DEFAULT 0,
  `posts` int(10) unsigned NOT NULL DEFAULT 0,
  `lastdate` int(10) unsigned DEFAULT NULL,
  `lastuser` int(10) unsigned DEFAULT NULL,
  `lastid` int(10) unsigned DEFAULT NULL,
  `minread` tinyint(4) NOT NULL DEFAULT -1,
  `minthread` tinyint(4) NOT NULL DEFAULT 1,
  `minreply` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `cat` (`cat`),
  CONSTRAINT `z_forums_ibfk_1` FOREIGN KEY (`cat`) REFERENCES `z_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_forumsread` (
  `uid` int(10) unsigned NOT NULL,
  `fid` int(5) unsigned NOT NULL,
  `time` int(11) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`fid`),
  KEY `fid` (`fid`),
  CONSTRAINT `z_forumsread_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `z_forumsread_ibfk_4` FOREIGN KEY (`fid`) REFERENCES `z_forums` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_pmsgs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` int(10) unsigned NOT NULL DEFAULT 0,
  `userto` int(10) unsigned NOT NULL,
  `userfrom` int(10) unsigned NOT NULL,
  `unread` tinyint(1) unsigned NOT NULL DEFAULT 1,
  `del_from` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `del_to` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `userto` (`userto`),
  KEY `userfrom` (`userfrom`),
  CONSTRAINT `z_pmsgs_ibfk_1` FOREIGN KEY (`userto`) REFERENCES `users` (`id`),
  CONSTRAINT `z_pmsgs_ibfk_2` FOREIGN KEY (`userfrom`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` int(10) unsigned NOT NULL,
  `thread` int(10) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `revision` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `threadid` (`thread`),
  KEY `user` (`user`),
  CONSTRAINT `z_posts_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `z_posts_ibfk_2` FOREIGN KEY (`thread`) REFERENCES `z_threads` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_poststext` (
  `id` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `revision` smallint(5) unsigned NOT NULL DEFAULT 1,
  `date` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`,`revision`),
  CONSTRAINT `z_poststext_ibfk_1` FOREIGN KEY (`id`) REFERENCES `z_posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_threads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `forum` int(5) unsigned NOT NULL DEFAULT 0,
  `title` varchar(100) NOT NULL,
  `user` int(10) unsigned DEFAULT NULL,
  `posts` int(10) unsigned NOT NULL DEFAULT 1,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `lastdate` int(10) unsigned DEFAULT NULL,
  `lastuser` int(10) unsigned DEFAULT NULL,
  `lastid` int(10) unsigned DEFAULT NULL,
  `closed` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `forum` (`forum`),
  KEY `user` (`user`),
  KEY `lastuser` (`lastuser`),
  KEY `lastid` (`lastid`),
  CONSTRAINT `z_threads_ibfk_1` FOREIGN KEY (`forum`) REFERENCES `z_forums` (`id`),
  CONSTRAINT `z_threads_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `z_threads_ibfk_5` FOREIGN KEY (`lastuser`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `z_threads_ibfk_6` FOREIGN KEY (`lastid`) REFERENCES `z_posts` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `z_threadsread` (
  `uid` int(10) unsigned NOT NULL,
  `tid` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  UNIQUE KEY `uid` (`uid`,`tid`),
  KEY `tid` (`tid`),
  CONSTRAINT `z_threadsread_ibfk_3` FOREIGN KEY (`uid`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `z_threadsread_ibfk_4` FOREIGN KEY (`tid`) REFERENCES `z_threads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2023-08-03 19:14:17
