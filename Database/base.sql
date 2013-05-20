-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 20, 2013 at 07:13 PM
-- Server version: 5.1.66-community
-- PHP Version: 5.3.21

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `doxie`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `creator` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updator` int(11) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `default` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cookie_data`
--

CREATE TABLE IF NOT EXISTS `cookie_data` (
  `cookie_id` varchar(255) NOT NULL,
  `varname` varchar(100) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `image` text,
  `author` varchar(250) NOT NULL,
  `version` varchar(10) NOT NULL,
  `installed` bit(1) NOT NULL DEFAULT b'0',
  `custom` bit(1) NOT NULL DEFAULT b'0',
  `active` bit(1) NOT NULL DEFAULT b'0',
  `tab` int(1) NOT NULL DEFAULT '0',
  `ui` int(1) NOT NULL DEFAULT '0',
  `visible` bit(1) NOT NULL DEFAULT b'1',
  `authkey` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`plugin_id`),
  KEY `active` (`active`,`ui`),
  KEY `visible` (`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`plugin_id`, `name`, `image`, `author`, `version`, `installed`, `custom`, `active`, `tab`, `ui`, `visible`, `authkey`) VALUES
(1, 'Content', '/image/admin/Modify.png', 'Lightweight CMS', '1.0', b'1', b'0', b'1', 1, 1, b'0', 'd2ffbc5f90522e8130698de1ec54ba7f48a025d5'),
(2, 'Plugins', '/image/admin/Picture.png', 'Lightweight CMS', '1.0', b'1', b'0', b'1', 1, 1, b'0', 'd2ffbc5f90522e8130698de1ec54ba7f48a025d5'),
(3, 'Navigation', NULL, 'Lightweight CMS', '1.0', b'1', b'0', b'0', 0, 1, b'1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plugin_data`
--

CREATE TABLE IF NOT EXISTS `plugin_data` (
  `data_id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) NOT NULL,
  `var_name` int(11) NOT NULL,
  `var_value` int(11) NOT NULL,
  PRIMARY KEY (`data_id`),
  KEY `plugin_id` (`plugin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_events`
--

CREATE TABLE IF NOT EXISTS `plugin_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) NOT NULL,
  `event_name` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `registered_events`
--

CREATE TABLE IF NOT EXISTS `registered_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pe_id` int(11) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `code` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `ip` varchar(46) NOT NULL,
  `session_id` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `is_admin` bit(1) NOT NULL DEFAULT b'0',
  `is_banned` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
