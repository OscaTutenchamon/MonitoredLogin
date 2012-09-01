-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 01, 2012 at 09:06 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `ml_blocked_ip`
--

CREATE TABLE IF NOT EXISTS `ml_blocked_ip` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `blocked_until` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ml_blocked_user`
--

CREATE TABLE IF NOT EXISTS `ml_blocked_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `blocked_until` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ml_login_attempt`
--

CREATE TABLE IF NOT EXISTS `ml_login_attempt` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` char(128) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `login` char(128) NOT NULL,
  `wrong` tinyint(1) unsigned NOT NULL,
  `browser` char(20) NOT NULL,
  `platform` char(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ip` (`ip`),
  KEY `time` (`time`),
  KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
