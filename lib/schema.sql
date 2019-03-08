# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.38)
# Database: publications
# Generation Time: 2018-10-23 10:36:15 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table affiliations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `affiliations`;

CREATE TABLE `affiliations` (
  `id` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(100) DEFAULT NULL,
  `search` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `clarity_lab_uri` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table labs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `labs`;

CREATE TABLE `labs` (
  `lab_clarity_uri` varchar(100) NOT NULL DEFAULT '',
  `lab_status` varchar(10) DEFAULT NULL,
  `lab_name` varchar(100) NOT NULL DEFAULT '',
  `lab_affiliation` varchar(50) DEFAULT NULL,
  `lab_pi` varchar(100) DEFAULT NULL,
  `log` text,
  PRIMARY KEY (`lab_clarity_uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table login_attempts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table publications
# ------------------------------------------------------------

DROP TABLE IF EXISTS `publications`;

CREATE TABLE `publications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pmid` int(11) DEFAULT NULL,
  `doi` varchar(100) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `keywords` text,
  `submitted` date DEFAULT NULL,
  `pubdate` date DEFAULT NULL,
  `journal` varchar(100) DEFAULT NULL,
  `volume` varchar(10) DEFAULT NULL,
  `issue` varchar(10) DEFAULT NULL,
  `pages` varchar(20) DEFAULT NULL,
  `title` text,
  `abstract` text,
  `authors` text,
  `status` varchar(20) DEFAULT NULL,
  `reservation_user` varchar(100) DEFAULT NULL,
  `reservation_timestamp` int(10) DEFAULT NULL,
  `comments` text,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table publications_text
# ------------------------------------------------------------

DROP TABLE IF EXISTS `publications_text`;

CREATE TABLE `publications_text` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publication_id` int(11) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `text` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table publications_xref
# ------------------------------------------------------------

DROP TABLE IF EXISTS `publications_xref`;

CREATE TABLE `publications_xref` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `publication_id` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table researchers
# ------------------------------------------------------------

DROP TABLE IF EXISTS `researchers`;

CREATE TABLE `researchers` (
  `email` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `query_name` varchar(100) DEFAULT NULL,
  `log` text,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_fname` varchar(100) DEFAULT '',
  `user_lname` varchar(100) DEFAULT NULL,
  `user_hash` varchar(128) NOT NULL,
  `user_auth` int(1) NOT NULL DEFAULT '0',
  `user_status` int(1) NOT NULL DEFAULT '0',
  `log` text,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
