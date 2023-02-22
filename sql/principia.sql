-- Adminer 4.8.1 MySQL 10.11.2-MariaDB dump

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
  `title` varchar(128) NOT NULL DEFAULT 'A contest',
  `description` text NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'assets/placeholder.png',
  `active` tinyint(1) unsigned NOT NULL DEFAULT 1,
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
  CONSTRAINT `leaderboard_ibfk_2` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `title` varchar(128) NOT NULL DEFAULT 'A level',
  `description` text NOT NULL,
  `author` int(10) unsigned NOT NULL DEFAULT 1,
  `time` int(10) unsigned NOT NULL DEFAULT 0,
  `parent` int(10) unsigned DEFAULT NULL,
  `revision` int(10) unsigned NOT NULL DEFAULT 1,
  `revision_time` int(10) unsigned NOT NULL DEFAULT 0,
  `likes` int(10) unsigned NOT NULL DEFAULT 0,
  `derivatives` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `visibility` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `views` int(10) unsigned NOT NULL DEFAULT 0,
  `downloads` int(10) unsigned NOT NULL DEFAULT 0,
  `platform` varchar(32) NOT NULL DEFAULT 'Samsung Smart Fridge',
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  CONSTRAINT `levels_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `likes` (
  `user` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  KEY `user` (`user`),
  KEY `level` (`level`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`level`) REFERENCES `levels` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT 'Lorem ipsum',
  `text` text DEFAULT NULL,
  `time` int(10) unsigned DEFAULT 0,
  `redirect` varchar(256) DEFAULT NULL,
  `author` int(10) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
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
  `title` varchar(128) NOT NULL DEFAULT 'A package',
  `description` text NOT NULL,
  `author` int(10) unsigned NOT NULL DEFAULT 1,
  `time` int(10) unsigned NOT NULL DEFAULT 0,
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


CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(64) NOT NULL,
  `email` char(64) NOT NULL,
  `ip` char(15) NOT NULL DEFAULT '999.999.999.999',
  `token` char(40) NOT NULL,
  `joined` int(10) unsigned NOT NULL DEFAULT 0,
  `lastview` int(10) unsigned NOT NULL DEFAULT 0,
  `lastpost` int(10) unsigned NOT NULL DEFAULT 0,
  `darkmode` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `avatar` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `powerlevel` tinyint(4) NOT NULL DEFAULT 1,
  `posts` int(10) unsigned NOT NULL DEFAULT 0,
  `threads` int(10) unsigned NOT NULL DEFAULT 0,
  `archivename` varchar(128) DEFAULT NULL,
  `customcolor` char(6) DEFAULT NULL,
  `title` varchar(256) DEFAULT NULL,
  `timezone` varchar(64) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `location` varchar(128) DEFAULT NULL,
  `signature` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2023-02-22 20:17:06
