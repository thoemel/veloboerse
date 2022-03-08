-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 08, 2022 at 04:31 PM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `probern_boerse_leer`
--
CREATE DATABASE IF NOT EXISTS `probern_boerse_leer` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `probern_boerse_leer`;

-- --------------------------------------------------------

--
-- Table structure for table `acl`
--

DROP TABLE IF EXISTS `acl`;
CREATE TABLE `acl` (
  `ai` int(10) UNSIGNED NOT NULL,
  `action_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_actions`
--

DROP TABLE IF EXISTS `acl_actions`;
CREATE TABLE `acl_actions` (
  `action_id` int(10) UNSIGNED NOT NULL,
  `action_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `action_desc` varchar(100) NOT NULL COMMENT 'Human readable description',
  `category_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `acl_categories`
--

DROP TABLE IF EXISTS `acl_categories`;
CREATE TABLE `acl_categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `category_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `category_desc` varchar(100) NOT NULL COMMENT 'Human readable description'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--

DROP TABLE IF EXISTS `auth_sessions`;
CREATE TABLE `auth_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `boersen`
--

DROP TABLE IF EXISTS `boersen`;
CREATE TABLE `boersen` (
  `id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `status` enum('offen','geschlossen') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

DROP TABLE IF EXISTS `denied_access`;
CREATE TABLE `denied_access` (
  `ai` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(1) UNSIGNED DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `haendler`
--

DROP TABLE IF EXISTS `haendler`;
CREATE TABLE `haendler` (
  `id` int(11) NOT NULL,
  `code` varchar(36) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Fuer Direktlink',
  `firma` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `person` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bankverbindung` text COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kommentar` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('offen','angenommen','abgeholt','ausbezahlt') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'offen',
  `provision` float NOT NULL DEFAULT 15 COMMENT '%',
  `standgebuehr` float(5,2) DEFAULT NULL,
  `busse` float NOT NULL DEFAULT 0,
  `uptodate` tinyint(4) NOT NULL DEFAULT 0,
  `anzahlVelos` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

DROP TABLE IF EXISTS `ips_on_hold`;
CREATE TABLE `ips_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

DROP TABLE IF EXISTS `login_errors`;
CREATE TABLE `login_errors` (
  `ai` int(10) UNSIGNED NOT NULL,
  `username_or_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `private`
--

DROP TABLE IF EXISTS `private`;
CREATE TABLE `private` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `strasse` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `plz` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT 'auch Nachbarlaender moeglich',
  `ort` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(16) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `private`
--

INSERT INTO `private` (`id`, `user_id`, `vorname`, `nachname`, `adresse`, `iban`, `strasse`, `plz`, `ort`, `telefon`) VALUES
(14, 2981469148, 'Der', 'Superadmin', '', '', 'Birkenweg 61', '3013', 'Bern', ''),
(15, 2507898819, 'Helferin', 'Helfer', '', '', 'Birkenweg 61', '3013', 'Bern', ''),
(18, 3623731366, 'Dominik', 'Guggisberg', '', 'afsadf', 'Birkenweg 61', '3013', 'Bern', '0313185410'),
(83, 1082761793, 'Thomas', 'Schlegel', '', '', 'Lorrainestrasse 66', '3014', 'Bern', '078 604 59 98');

-- --------------------------------------------------------

--
-- Table structure for table `provision`
--

DROP TABLE IF EXISTS `provision`;
CREATE TABLE `provision` (
  `preis` int(11) NOT NULL COMMENT 'Obergrenze des Preisbandes',
  `provision` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Provision für Private';

--
-- Dumping data for table `provision`
--

INSERT INTO `provision` (`preis`, `provision`) VALUES
(20, 0),
(50, 5),
(70, 10),
(100, 15),
(120, 20),
(150, 25),
(170, 30),
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
(1000, 150);

-- --------------------------------------------------------

--
-- Table structure for table `rahmennummern`
--

DROP TABLE IF EXISTS `rahmennummern`;
CREATE TABLE `rahmennummern` (
  `id` int(11) NOT NULL,
  `preis` int(11) NOT NULL,
  `verkaeufer_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `typ` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `farbe` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `marke` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `rahmennummer` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Für Export zur Polizei';

-- --------------------------------------------------------

--
-- Table structure for table `statistik`
--

DROP TABLE IF EXISTS `statistik`;
CREATE TABLE `statistik` (
  `id` int(11) NOT NULL,
  `zeitpunkt` datetime NOT NULL,
  `url` varchar(255) CHARACTER SET ascii NOT NULL,
  `post_json` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

DROP TABLE IF EXISTS `username_or_email_on_hold`;
CREATE TABLE `username_or_email_on_hold` (
  `ai` int(10) UNSIGNED NOT NULL,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_level` tinyint(3) UNSIGNED NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd` varchar(60) NOT NULL,
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `passwd_modified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `auth_level`, `banned`, `passwd`, `passwd_recovery_code`, `passwd_recovery_date`, `passwd_modified_at`, `last_login`, `created_at`, `modified_at`) VALUES
(1082761793, 'thoemel', 'thoemel@thoemel.ch', 9, '0', '$2y$11$eY.qsWylDEbjicbuvHcO..u0ez/LjUzz1o8NcROilCZO96A3m170S', '', NULL, '2021-03-21 12:49:00', '2021-03-21 12:49:00', '2020-09-03 14:22:49', '2021-03-21 11:49:00'),
(2507898819, '20', 'vierzehn@provelobern.ch', 9, '0', '$2y$11$w9yFSjAFPJp6S5xHgwiNr.hvbjb0WtXdjt4s64LQ7ix57mDEdNzP.', '', NULL, '2020-09-05 14:27:24', '2020-09-05 14:27:24', '2020-08-22 20:49:07', '2020-09-05 12:27:24'),
(2981469148, 'maschti', 'boerse@provelobern.ch', 9, '0', '$2y$11$T9p3YDOfvapVa1OIwBmShuSgbo1qbPQVGurYRIjTakV6X2eeHAFEm', '', NULL, '2020-09-05 17:40:37', '2020-09-05 17:40:37', '2020-08-22 20:41:12', '2020-09-05 15:40:37'),
(3623731366, 'dg', 'dg@provelobern.ch', 9, '0', '$2y$11$Hw.agSyv5NMd0eyGOMKNE.CqvXI/Ux4lzqsA0Yc/u3pgD7h30KW.W', '', NULL, '2020-08-27 14:31:29', '2020-08-27 14:31:29', '2020-08-23 08:51:17', '2020-08-27 12:31:29');

--
-- Triggers `users`
--
DROP TRIGGER IF EXISTS `ca_passwd_trigger`;
DELIMITER $$
CREATE TRIGGER `ca_passwd_trigger` BEFORE UPDATE ON `users` FOR EACH ROW BEGIN
    IF (NEW.passwd <=> OLD.passwd) THEN
        SET NEW.passwd_modified_at = NOW();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `velos`
--

DROP TABLE IF EXISTS `velos`;
CREATE TABLE `velos` (
  `id` int(11) NOT NULL COMMENT 'Quittungsnummer',
  `preis` int(11) NOT NULL,
  `angenommen` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `verkauft` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `abgeholt` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `zahlungsart` enum('bar','kredit','debit') COLLATE utf8_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ausbezahlt` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `kein_ausweis` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `keine_provision` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `helfer_kauft` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `haendler_id` int(11) NOT NULL DEFAULT 0,
  `verkaeufer_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `typ` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `farbe` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marke` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rahmennummer` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vignettennummer` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gestohlen` tinyint(1) NOT NULL DEFAULT 0,
  `storniert` tinyint(1) NOT NULL DEFAULT 0,
  `problemfall` tinyint(1) NOT NULL DEFAULT 0,
  `afrika` tinyint(1) NOT NULL DEFAULT 0,
  `bemerkungen` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acl`
--
ALTER TABLE `acl`
  ADD PRIMARY KEY (`ai`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `acl_actions`
--
ALTER TABLE `acl_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `acl_categories`
--
ALTER TABLE `acl_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_code` (`category_code`),
  ADD UNIQUE KEY `category_desc` (`category_desc`);

--
-- Indexes for table `auth_sessions`
--
ALTER TABLE `auth_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boersen`
--
ALTER TABLE `boersen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datum` (`datum`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `denied_access`
--
ALTER TABLE `denied_access`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `haendler`
--
ALTER TABLE `haendler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_code` (`code`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `ips_on_hold`
--
ALTER TABLE `ips_on_hold`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `login_errors`
--
ALTER TABLE `login_errors`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `private`
--
ALTER TABLE `private`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_private_user_id` (`user_id`);

--
-- Indexes for table `provision`
--
ALTER TABLE `provision`
  ADD PRIMARY KEY (`preis`),
  ADD UNIQUE KEY `provision` (`provision`);

--
-- Indexes for table `rahmennummern`
--
ALTER TABLE `rahmennummern`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statistik`
--
ALTER TABLE `statistik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_zeitpunkt` (`zeitpunkt`),
  ADD KEY `idx_user` (`user_id`);

--
-- Indexes for table `username_or_email_on_hold`
--
ALTER TABLE `username_or_email_on_hold`
  ADD PRIMARY KEY (`ai`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `velos`
--
ALTER TABLE `velos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verkauft` (`verkauft`),
  ADD KEY `ausbezahlt` (`ausbezahlt`),
  ADD KEY `zahlungsart` (`zahlungsart`),
  ADD KEY `helfer_kauft` (`helfer_kauft`),
  ADD KEY `abgeholt` (`abgeholt`),
  ADD KEY `haendler_nr` (`haendler_id`),
  ADD KEY `afrika` (`afrika`),
  ADD KEY `idx_angenommen` (`angenommen`),
  ADD KEY `idx_verkaeufy` (`verkaeufer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acl`
--
ALTER TABLE `acl`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acl_actions`
--
ALTER TABLE `acl_actions`
  MODIFY `action_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `acl_categories`
--
ALTER TABLE `acl_categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boersen`
--
ALTER TABLE `boersen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `denied_access`
--
ALTER TABLE `denied_access`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `haendler`
--
ALTER TABLE `haendler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ips_on_hold`
--
ALTER TABLE `ips_on_hold`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `login_errors`
--
ALTER TABLE `login_errors`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `private`
--
ALTER TABLE `private`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `statistik`
--
ALTER TABLE `statistik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `username_or_email_on_hold`
--
ALTER TABLE `username_or_email_on_hold`
  MODIFY `ai` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acl`
--
ALTER TABLE `acl`
  ADD CONSTRAINT `acl_ibfk_1` FOREIGN KEY (`action_id`) REFERENCES `acl_actions` (`action_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `acl_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_old` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `acl_actions`
--
ALTER TABLE `acl_actions`
  ADD CONSTRAINT `acl_actions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `acl_categories` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
