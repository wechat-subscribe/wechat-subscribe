# Host: localhost  (Version: 5.5.40)
# Date: 2015-10-16 14:14:02
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "wx_activity"
#

DROP TABLE IF EXISTS `wx_activity`;
CREATE TABLE `wx_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `fronturl` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

#
# Structure for table "wx_activity_interact_project"
#

DROP TABLE IF EXISTS `wx_activity_interact_project`;
CREATE TABLE `wx_activity_interact_project` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `review` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` int(4) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

#
# Structure for table "wx_vote_interact"
#

DROP TABLE IF EXISTS `wx_vote_interact`;
CREATE TABLE `wx_vote_interact` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `voteId` int(4) DEFAULT NULL,
  `openId` int(4) DEFAULT NULL,
  `useId` int(4) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `optionId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

#
# Structure for table "wx_vote_option"
#

DROP TABLE IF EXISTS `wx_vote_option`;
CREATE TABLE `wx_vote_option` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `voteId` int(4) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
