-- DB Changes for new Branch "online Erfassung"

--
-- Community Auth - MySQL database setup
--
-- Community Auth is an open source authentication application for CodeIgniter 3
--
-- @package     Community Auth
-- @author      Robert B Gottier
-- @copyright   Copyright (c) 2011 - 2018, Robert B Gottier. (http://brianswebdesign.com/)
-- @license     BSD - http://www.opensource.org/licenses/BSD-3-Clause
-- @link        http://community-auth.com
--

--
-- Table structure for table `ci_sessions`
--
-- Note that if sess_match_ip == true, then the 
-- primary key for ci_sessions needs to be changed after table creation:
--
-- ALTER TABLE ci_sessions DROP PRIMARY KEY;
-- ALTER TABLE ci_sessions ADD PRIMARY KEY (id, ip_address);

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE = MyISAM DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_sessions`
--
-- The IP address and user agent are only 
-- included so that sessions can be identified.
-- This can be helpful if you want to show the 
-- user their sessions, and allow them to terminate
-- selected sessions.
--

CREATE TABLE IF NOT EXISTS `auth_sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ips_on_hold`
--

CREATE TABLE IF NOT EXISTS `ips_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE = MyISAM DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_errors`
--

CREATE TABLE IF NOT EXISTS `login_errors` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE = MyISAM DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `denied_access`
--

CREATE TABLE IF NOT EXISTS `denied_access` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `time` datetime NOT NULL,
  `reason_code` tinyint(1) unsigned DEFAULT 0,
  PRIMARY KEY (`ai`)
) ENGINE = MyISAM DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `username_or_email_on_hold`
--

CREATE TABLE IF NOT EXISTS `username_or_email_on_hold` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username_or_email` varchar(255) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`ai`)
) ENGINE = MyISAM DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `auth_level` tinyint(3) unsigned NOT NULL,
  `banned` enum('0','1') NOT NULL DEFAULT '0',
  `passwd` varchar(60) NOT NULL,
  `passwd_recovery_code` varchar(60) DEFAULT NULL,
  `passwd_recovery_date` datetime DEFAULT NULL,
  `passwd_modified_at` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Trigger updates passwd_modified_at field if passwd modified
--

delimiter //

CREATE TRIGGER ca_passwd_trigger 
BEFORE UPDATE 
ON users FOR EACH ROW
BEGIN
    IF (NEW.passwd <=> OLD.passwd) THEN
        SET NEW.passwd_modified_at = NOW();
    END IF;
END;//

delimiter ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_categories`
-- 

CREATE TABLE `acl_categories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `category_desc` varchar(100) NOT NULL COMMENT 'Human readable description',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `category_code` (`category_code`),
  UNIQUE KEY `category_desc` (`category_desc`)
) ENGINE = InnoDB DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acl_actions`
-- 

CREATE TABLE `acl_actions` (
  `action_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action_code` varchar(100) NOT NULL COMMENT 'No periods allowed!',
  `action_desc` varchar(100) NOT NULL COMMENT 'Human readable description',
  `category_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`action_id`),
  FOREIGN KEY (`category_id`) REFERENCES `acl_categories`(`category_id`) 
  ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `acl`
-- 

CREATE TABLE `acl` (
  `ai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`ai`),
  FOREIGN KEY (`action_id`) REFERENCES `acl_actions`(`action_id`) 
  ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) 
  ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

-- --------------------------------------------------------





CREATE TABLE IF NOT EXISTS `private` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_private_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Adresse auf mehrere Felder aufteilen
ALTER TABLE `private` ADD `strasse` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `iban`, ADD `plz` VARCHAR(8) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'auch Nachbarlaender moeglich' AFTER `strasse`, ADD `ort` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `plz`;

-- Telefon
ALTER TABLE `private` ADD `telefon` VARCHAR(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `ort`;

-- Not related to authentication library
ALTER TABLE `velos` ADD `verkaeufer_id` int(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `haendler_id`, ADD INDEX `idx_verkaeufy` (`verkaeufer_id`);

ALTER TABLE `velos` ADD `angenommen` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no' AFTER `preis`, ADD INDEX `idx_angenommen` (`angenommen`);


-- Rahmennummern fuer Polizei
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `rahmennummern` (
  `id` int(11) NOT NULL,
  `hoechste_quittung` int(11) NOT NULL,
  `datum` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Für den Export der Rahmennummern für die Polizei';

ALTER TABLE `rahmennummern`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_datum` (`datum`);

ALTER TABLE `rahmennummern`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- --------------------------------------------------------

--
-- Insert Superuser
--
INSERT INTO `users` (`user_id`, `username`, `email`, `auth_level`, `banned`, `passwd`, `passwd_recovery_code`, `passwd_recovery_date`, `passwd_modified_at`, `last_login`, `created_at`, `modified_at`) VALUES
(2981469148, 'maschti', 'boerse@provelobern.ch', 9, '0', '$2y$11$T9p3YDOfvapVa1OIwBmShuSgbo1qbPQVGurYRIjTakV6X2eeHAFEm', '', NULL, '2020-08-22 20:41:12', NULL, '2020-08-22 20:41:12', '2020-08-22 18:41:12');

INSERT INTO `private` (`id`, `user_id`, `vorname`, `nachname`, `adresse`, `iban`, `strasse`, `plz`, `ort`, `telefon`) VALUES
(14, 2981469148, 'Der', 'Superadmin', '', '', 'Birkenweg 61', '3013', 'Bern', '');



