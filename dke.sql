-- phpMyAdmin SQL Dump
-- version 3.3.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 19, 2010 at 09:32 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dke`
--

-- --------------------------------------------------------

--
-- Table structure for table `login_cookies`
--

CREATE TABLE IF NOT EXISTS `login_cookies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_login_user` int(11) unsigned NOT NULL,
  `code` varchar(255) NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_login_user` (`parent_login_user`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `login_cookies`
--


-- --------------------------------------------------------

--
-- Table structure for table `login_object`
--

CREATE TABLE IF NOT EXISTS `login_object` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;


-- --------------------------------------------------------

--
-- Table structure for table `login_object_to_roles`
--

CREATE TABLE IF NOT EXISTS `login_object_to_roles` (
  `login_object_id` int(11) unsigned NOT NULL,
  `roles` enum('admin','guest','brother','pledge','rush') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`login_object_id`,`roles`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



-- --------------------------------------------------------

--
-- Table structure for table `oldusers`
--

CREATE TABLE IF NOT EXISTS `oldusers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `hometown` varchar(255) NOT NULL,
  `homestate` varchar(255) NOT NULL,
  `grad_year` int(4) NOT NULL,
  `pledge_year` int(4) NOT NULL,
  `pledge_sem` varchar(255) NOT NULL,
  `college` varchar(255) NOT NULL,
  `major` varchar(255) NOT NULL,
  `big_brother` varchar(255) NOT NULL,
  `shirt` varchar(255) NOT NULL,
  `netid` varchar(255) NOT NULL,
  `cuid` varchar(255) NOT NULL,
  `picture` text NOT NULL,
  `town` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `employer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;



-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `updated_by` int(11) unsigned NOT NULL,
  `last_updated` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages_versions`
--

CREATE TABLE IF NOT EXISTS `pages_versions` (
  `page_id` int(11) unsigned NOT NULL,
  `ordering` int(11) unsigned NOT NULL,
  `current` tinyint(1) unsigned NOT NULL,
  `content` text NOT NULL,
  `author` int(11) unsigned NOT NULL,
  `commit_time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `peoples_threads`
--

CREATE TABLE IF NOT EXISTS `peoples_threads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


-- --------------------------------------------------------

--
-- Table structure for table `peoples_threads_posts`
--

CREATE TABLE IF NOT EXISTS `peoples_threads_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) unsigned NOT NULL,
  `author` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;



-- --------------------------------------------------------

--
-- Table structure for table `rush_object`
--

CREATE TABLE IF NOT EXISTS `rush_object` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `netid` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `town` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `college` varchar(255) NOT NULL,
  `major` varchar(255) NOT NULL,
  `picture` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `contact1` varchar(255) NOT NULL,
  `contact2` varchar(255) NOT NULL,
  `contact3` varchar(255) NOT NULL,
  `dinner1` varchar(255) NOT NULL,
  `dinner2` varchar(255) NOT NULL,
  `dinner3` varchar(255) NOT NULL,
  `monday_night` varchar(255) NOT NULL,
  `tuesday_smoker` varchar(255) NOT NULL,
  `tuesday_night` varchar(255) NOT NULL,
  `wednesday_smoker` varchar(255) NOT NULL,
  `wednesday_night` varchar(255) NOT NULL,
  `thursday_smoker` varchar(255) NOT NULL,
  `thursday_night` varchar(255) NOT NULL,
  `friday_smoker` varchar(255) NOT NULL,
  `friday_night` varchar(255) NOT NULL,
  `saturday_smoker` varchar(255) NOT NULL,
  `saturday_night` varchar(255) NOT NULL,
  `sunday_night` varchar(255) NOT NULL,
  `wine_night` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=156 ;

--
-- Table structure for table `rush_object_to_comments`
--

CREATE TABLE IF NOT EXISTS `rush_object_to_comments` (
  `login_id` int(11) unsigned NOT NULL,
  `rush_id` int(11) unsigned NOT NULL,
  `comment` text NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
--
-- Table structure for table `rush_register`
--

CREATE TABLE IF NOT EXISTS `rush_register` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `netid` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `monday` tinyint(1) unsigned NOT NULL,
  `tuesday` tinyint(1) unsigned NOT NULL,
  `wednesday` tinyint(1) unsigned NOT NULL,
  `thursday` tinyint(1) unsigned NOT NULL,
  `friday` tinyint(1) unsigned NOT NULL,
  `pic_code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=126 ;


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `hometown` varchar(255) NOT NULL,
  `homestate` varchar(255) NOT NULL,
  `grad_year` int(4) NOT NULL,
  `pledge_year` int(4) NOT NULL,
  `pledge_sem` varchar(255) NOT NULL,
  `college` varchar(255) NOT NULL,
  `major` varchar(255) NOT NULL,
  `big_brother` varchar(255) NOT NULL,
  `shirt` varchar(255) NOT NULL,
  `netid` varchar(255) NOT NULL,
  `cuid` varchar(255) NOT NULL,
  `picture` text NOT NULL,
  `town` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `employer` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS `rush_pictures` (
  `time` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


