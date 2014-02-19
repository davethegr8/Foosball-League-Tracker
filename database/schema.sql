# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.35-0ubuntu0.12.04.2)
# Database: foos
# Generation Time: 2014-02-19 15:34:59 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table accounts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;

INSERT INTO `accounts` (`id`, `email`, `password`, `created`)
VALUES
  (43,'davethegr8@example.com','','2009-03-18 19:11:12'),
  (49,'dave@example.com','','2009-10-09 08:40:33'),
  (51,'user@example.com','5f4dcc3b5aa765d61d8327deb882cf99','2014-02-19 15:31:53');

/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table games
# ------------------------------------------------------------

DROP TABLE IF EXISTS `games`;

CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `side_1_score` int(11) DEFAULT NULL,
  `side_2_score` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;

INSERT INTO `games` (`id`, `account_id`, `side_1_score`, `side_2_score`, `created`)
VALUES
  (1,51,10,5,'2014-02-19 15:32:29'),
  (2,51,10,5,'2014-02-19 15:32:51'),
  (3,51,10,5,'2014-02-19 15:33:08'),
  (4,51,10,5,'2014-02-19 15:33:29'),
  (5,51,10,5,'2014-02-19 15:33:47'),
  (6,51,10,5,'2014-02-19 15:33:59');

/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table games_players
# ------------------------------------------------------------

DROP TABLE IF EXISTS `games_players`;

CREATE TABLE `games_players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL DEFAULT '0',
  `game_id` int(11) NOT NULL DEFAULT '0',
  `side` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_players` (`player_id`,`game_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `games_players` WRITE;
/*!40000 ALTER TABLE `games_players` DISABLE KEYS */;

INSERT INTO `games_players` (`id`, `player_id`, `game_id`, `side`)
VALUES
  (1,1,1,1),
  (2,2,1,1),
  (3,3,1,2),
  (4,4,1,2),
  (5,1,2,1),
  (6,3,2,1),
  (7,2,2,2),
  (8,4,2,2),
  (9,1,3,1),
  (10,4,3,1),
  (11,2,3,2),
  (12,3,3,2),
  (13,2,4,1),
  (14,3,4,1),
  (15,1,4,2),
  (16,4,4,2),
  (17,2,5,1),
  (18,4,5,1),
  (19,1,5,2),
  (20,3,5,2),
  (21,3,6,1),
  (22,4,6,1),
  (23,1,6,2),
  (24,2,6,2);

/*!40000 ALTER TABLE `games_players` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `modified` date DEFAULT NULL,
  `body` text,
  `active` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `slug`, `title`, `created`, `modified`, `body`, `active`)
VALUES
  (1,'about','About','2009-03-29','2009-03-29','<p>This is an application that I made in an evening to keep track of the games we played at work.</p>\n\n<h3>Version 2.0</h3>\n<p>&raquo; Conversion to CakePHP framework, replaced player graph.</p>\n\n<h3>Version 1.5.1</h3>\n<p>&raquo; Various bugfixes, formatting and layout fixes. Never launched, went straight to 2.0 instead.</p>\n\n<h3>Version 1.5</h3>\n<p>&raquo; Ranking algorithm installed.</p>\n\n<h3>Version 1.01</h3>\n<p>&raquo; Updated CSS and bug fixes. Games ordered by date played.</p>\n\n<h3>Version 1.0</h3>\n<p>&raquo; Initial Launch.</p>',1),
  (2,'contact','Contact','2009-03-29','2009-03-29','<p>To contact me, send an email to dave at zastica dot com. I\'ll get back to you as soon as I can.</p>',1);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table players
# ------------------------------------------------------------

DROP TABLE IF EXISTS `players`;

CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  `rank` int(11) DEFAULT '1000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;

INSERT INTO `players` (`id`, `account_id`, `name`, `rank`)
VALUES
  (1,51,'Player 1',1015),
  (2,51,'Player 2',1017),
  (3,51,'Player 3',1017),
  (4,51,'Player 4',1017);

/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `body` text,
  `active` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;

INSERT INTO `posts` (`id`, `title`, `subtitle`, `created`, `body`, `active`)
VALUES
  (1,'Score Tracker Launched','Version 1.0','2007-06-01 09:00:00','<p>I\'m announcing the launch of the Foosball League Score Tracker. It\'s super easy to use, just sign up, add your players, and begin to record games. Maybe if you\'re good enough, you can show up on the top players list. Easy as pie.</p>',1),
  (2,'Minor Updates','Version 1.01','2007-06-03 22:00:00','<p>Made some minor updates tonight, including a bugfix and some CSS updates. Games are now sorted by the date they were entered. Coming up soon: an updated rank algorithm.</p>',1),
  (3,'Updates: Rank Algorithm, CSS, and Stats','Version 1.5','2007-06-07 22:26:18','<p>I finished the first iteration of the ranking algorithm that this site uses. Rank is calculated based on how well you play in games and compared to how well you\'re expected to perform. The algorithm is subject to change at my whim, but it\'s as fair as I could make it. I\'d prefer to use an ELO or TrueSkill type algorithm, but it would take a lot more to develop. Sometime soon, perhaps.</p>',1),
  (4,'Announcing Version 2.0','','2009-03-29 00:00:00','<p>Well, it\'s been awhile since I did any development on foos.zastica. I recently decided to try out the CakePHP framework, and instead of creating an entirely new application, I decided to convert an existing one that I had already created. I chose this app because it was pretty straight forward, and didn\'t have a lot of odd features.</p>\n\n<p>About halfway through conversion, however, I learned that it wasn\'t as simple as I had thought. There are a lot of interesting database queries that need to be done to get the aggregate account and player data, and Cake isn\'t optimized to handle that. This left me doing a lot of direct queries on the database. Although I\'m sure that part of that is my lack of CakePHP knowledge as well.</p>\n\n<p>So, as of today, foos.zastica is now in v2.0. Most of the changes are backend type stuff, but there\'s a few front-end changes as well. I\'ve widened the site, changed around some styles, and changed the player graph to use the flot jquery library. This allows IE users to see the graph. It used to be in SVG, but some users weren\'t able to see the graph so it had to go.</p>\n\n<p>One of the bigger updates is the RSS syndication of updates. Whenever the site updates, you can get notified in your RSS reader. To subscribe, go to <a href=\"http://foos.zastica.com/posts/rss/\">/posts/rss/</a></p>\n\n<p>I\'ve also got some more changes up my sleeve, but I\'ll wait on announcing those until I\'ve got a better idea when they will be ready.</p>',1);

/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rank_track
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rank_track`;

CREATE TABLE `rank_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `players_id` int(11) DEFAULT NULL,
  `games_id` int(11) DEFAULT NULL,
  `rank` int(11) NOT NULL,
  `notes` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rank_track` WRITE;
/*!40000 ALTER TABLE `rank_track` DISABLE KEYS */;

INSERT INTO `rank_track` (`id`, `players_id`, `games_id`, `rank`, `notes`)
VALUES
  (1,1,1,1000,'Player 1, Player 2: 10; Player 3, Player 4: 5'),
  (2,2,1,1000,'Player 1, Player 2: 10; Player 3, Player 4: 5'),
  (3,3,1,1000,'Player 1, Player 2: 10; Player 3, Player 4: 5'),
  (4,4,1,1000,'Player 1, Player 2: 10; Player 3, Player 4: 5'),
  (5,1,2,1018,'Player 1, Player 3: 10; Player 2, Player 4: 5'),
  (6,3,2,988,'Player 1, Player 3: 10; Player 2, Player 4: 5'),
  (7,2,2,1018,'Player 1, Player 3: 10; Player 2, Player 4: 5'),
  (8,4,2,988,'Player 1, Player 3: 10; Player 2, Player 4: 5'),
  (9,1,3,1036,'Player 1, Player 4: 10; Player 2, Player 3: 5'),
  (10,4,3,976,'Player 1, Player 4: 10; Player 2, Player 3: 5'),
  (11,2,3,1006,'Player 1, Player 4: 10; Player 2, Player 3: 5'),
  (12,3,3,1006,'Player 1, Player 4: 10; Player 2, Player 3: 5'),
  (13,2,4,994,'Player 2, Player 3: 10; Player 1, Player 4: 5'),
  (14,3,4,994,'Player 2, Player 3: 10; Player 1, Player 4: 5'),
  (15,1,4,1054,'Player 2, Player 3: 10; Player 1, Player 4: 5'),
  (16,4,4,994,'Player 2, Player 3: 10; Player 1, Player 4: 5'),
  (17,2,5,1012,'Player 2, Player 4: 10; Player 1, Player 3: 5'),
  (18,4,5,981,'Player 2, Player 4: 10; Player 1, Player 3: 5'),
  (19,1,5,1041,'Player 2, Player 4: 10; Player 1, Player 3: 5'),
  (20,3,5,1012,'Player 2, Player 4: 10; Player 1, Player 3: 5'),
  (21,3,6,999,'Player 3, Player 4: 10; Player 1, Player 2: 5'),
  (22,4,6,999,'Player 3, Player 4: 10; Player 1, Player 2: 5'),
  (23,1,6,1028,'Player 3, Player 4: 10; Player 1, Player 2: 5'),
  (24,2,6,1030,'Player 3, Player 4: 10; Player 1, Player 2: 5');

/*!40000 ALTER TABLE `rank_track` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
