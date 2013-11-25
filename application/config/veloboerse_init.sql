-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2013 at 04:42 PM
-- Server version: 5.5.34-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `probern_boerse`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `haendler`
--

CREATE TABLE IF NOT EXISTS `haendler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firma` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8_unicode_ci,
  `person` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bankverbindung` text COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kommentar` text COLLATE utf8_unicode_ci,
  `status` enum('offen','angenommen','abgeholt','ausbezahlt') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offen',
  `provision` float NOT NULL DEFAULT '15' COMMENT '%',
  `standgebuehr` float(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `provision`
--

CREATE TABLE IF NOT EXISTS `provision` (
  `preis` int(11) NOT NULL COMMENT 'Obergrenze des Preisbandes',
  `provision` int(11) NOT NULL,
  PRIMARY KEY (`preis`),
  UNIQUE KEY `provision` (`provision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Provision für Private';

--
-- Dumping data for table `provision`
--

INSERT INTO `provision` (`preis`, `provision`) VALUES
(25, 0),
(50, 5),
(75, 10),
(100, 15),
(125, 20),
(150, 25),
(175, 30),
(200, 40),
(250, 50),
(300, 60),
(350, 70),
(400, 80),
(450, 90),
(500, 100),
(600, 110),
(700, 120),
(800, 130),
(900, 140),
(1000, 150),
(1100, 160),
(1200, 165),
(1300, 170),
(1400, 175),
(1500, 180),
(1600, 185),
(1800, 190),
(2000, 200),
(2200, 220),
(2400, 240),
(2600, 260),
(2800, 280),
(3000, 300);

-- --------------------------------------------------------

--
-- Table structure for table `statistik`
--

CREATE TABLE IF NOT EXISTS `statistik` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zeitpunkt` datetime NOT NULL,
  `url` varchar(255) CHARACTER SET ascii NOT NULL,
  `post_json` text COLLATE utf8_unicode_ci,
  `user_id` int(11) NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_zeitpunkt` (`zeitpunkt`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4802 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL DEFAULT '',
  `user_pass` varchar(60) NOT NULL DEFAULT '',
  `user_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_last_login` datetime DEFAULT NULL,
  `user_role` enum('superadmin','provelo','haendler') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_email`, `user_pass`, `user_date`, `user_modified`, `user_last_login`, `user_role`) VALUES
(4, 'maschti', '$2a$08$mai/bqeDvALOs5OWn6jgROoKI7MU92cfemqpjIqN7H7MjC9IHuxja', '2013-11-25 16:43:56', '2013-11-25 16:43:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `velos`
--

CREATE TABLE IF NOT EXISTS `velos` (
  `id` int(11) NOT NULL COMMENT 'Quittungsnummer',
  `preis` int(11) NOT NULL,
  `verkauft` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `abgeholt` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `zahlungsart` enum('bar','karte') COLLATE utf8_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ausbezahlt` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `kein_ausweis` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `keine_provision` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `helfer_kauft` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `haendler_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verkauft` (`verkauft`),
  KEY `ausbezahlt` (`ausbezahlt`),
  KEY `zahlungsart` (`zahlungsart`),
  KEY `helfer_kauft` (`helfer_kauft`),
  KEY `abgeholt` (`abgeholt`),
  KEY `haendler_nr` (`haendler_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
