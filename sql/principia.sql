-- Adminer 4.7.8 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `level` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `message` text NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT 'A contest',
  `description` text NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'assets/placeholder.png',
  `active` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `contests_entries` (
  `contest` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `ranking` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ipbans` (
  `ip` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  `reason` varchar(255) NOT NULL DEFAULT '<em>No reason specified</em>'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` tinyint(4) NOT NULL DEFAULT 1,
  `title` varchar(128) NOT NULL DEFAULT 'A level',
  `description` text NOT NULL,
  `author` int(11) NOT NULL DEFAULT 1,
  `time` int(11) NOT NULL DEFAULT 0,
  `likes` int(11) NOT NULL DEFAULT 0,
  `derivatives` tinyint(4) NOT NULL DEFAULT 0,
  `hidden` tinyint(4) NOT NULL DEFAULT 0,
  `locked` tinyint(4) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `downloads` int(11) NOT NULL DEFAULT 0,
  `platform` varchar(128) NOT NULL DEFAULT 'Samsung Smart Fridge',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `likes` (
  `user` int(11) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL DEFAULT 'Lorem ipsum',
  `text` text DEFAULT NULL,
  `time` bigint(20) DEFAULT 0,
  `redirect` varchar(256) DEFAULT NULL,
  `author_userid` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `customcolor` varchar(6) DEFAULT NULL,
  `powerlevel` int(11) NOT NULL DEFAULT 1,
  `levels` int(11) NOT NULL DEFAULT 0,
  `comments` int(11) NOT NULL DEFAULT 0,
  `darkmode` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2020-12-20 18:52:03
