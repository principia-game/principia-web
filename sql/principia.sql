-- principia-web database, dumped with Adminer

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `bans` (
  `user` int(10) unsigned NOT NULL,
  `banner` int(10) unsigned NOT NULL,
  `reason` varchar(255) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `old` tinyint(1) DEFAULT NULL,
  KEY `user` (`user`),
  CONSTRAINT `bans_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `level` int(10) unsigned NOT NULL,
  `author` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `contests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(128) NOT NULL,
  `time_from` datetime DEFAULT NULL,
  `time_to` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `contests_entries` (
  `contest` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `ranking` tinyint(4) NOT NULL DEFAULT 0,
  KEY `contest` (`contest`),
  KEY `level` (`level`),
  CONSTRAINT `contests_entries_ibfk_1` FOREIGN KEY (`contest`) REFERENCES `contests` (`id`),
  CONSTRAINT `contests_entries_ibfk_2` FOREIGN KEY (`level`) REFERENCES `levels` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `featured` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  CONSTRAINT `featured_ibfk_1` FOREIGN KEY (`level`) REFERENCES `levels` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `ipbans` (
  `ip` char(16) NOT NULL DEFAULT '0.0.0.0',
  `reason` varchar(255) NOT NULL DEFAULT '<em>No reason specified</em>',
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `leaderboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `score` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `user` (`user`),
  CONSTRAINT `leaderboard_ibfk_1` FOREIGN KEY (`level`) REFERENCES `levels` (`id`),
  CONSTRAINT `leaderboard_ibfk_3` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat` tinyint(3) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `author` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned DEFAULT NULL,
  `revision` int(10) unsigned NOT NULL DEFAULT 1,
  `revision_time` int(10) unsigned DEFAULT NULL,
  `likes` int(10) unsigned NOT NULL DEFAULT 0,
  `visibility` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `downloads` int(10) unsigned NOT NULL DEFAULT 0,
  `platform` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  KEY `visibility` (`visibility`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `description` (`description`),
  CONSTRAINT `levels_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `likes` (
  `user` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  KEY `user` (`user`),
  KEY `level` (`level`),
  CONSTRAINT `likes_ibfk_4` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_5` FOREIGN KEY (`level`) REFERENCES `levels` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL,
  `level` int(10) unsigned DEFAULT NULL,
  `recipient` int(10) unsigned NOT NULL,
  `sender` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`),
  KEY `sender` (`sender`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`recipient`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `description` text NOT NULL,
  `author` int(10) unsigned DEFAULT NULL,
  `time` int(10) unsigned DEFAULT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `downloads` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `passwordresets` (
  `id` char(64) NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `passwordresets_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(256) NOT NULL,
  `message` text NOT NULL,
  `user` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `email` char(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `token` char(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `joined` int(10) unsigned NOT NULL DEFAULT 0,
  `lastview` int(10) unsigned NOT NULL DEFAULT 0,
  `lastpost` int(10) unsigned NOT NULL DEFAULT 0,
  `avatar` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `rank` tinyint(4) NOT NULL DEFAULT 1,
  `posts` int(10) unsigned NOT NULL DEFAULT 0,
  `threads` int(10) unsigned NOT NULL DEFAULT 0,
  `archivename` varchar(128) DEFAULT NULL,
  `customcolor` char(6) DEFAULT NULL,
  `timezone` varchar(64) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `signature` text DEFAULT NULL,
  `pronouns` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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


-- 2024-12-18 21:44:22
