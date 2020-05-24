-- DB Changes for new Branch "online Erfassung"
ALTER TABLE `velos` ADD `verkaeufer_id` INT(11) NOT NULL DEFAULT '0' AFTER `haendler_id`;

ALTER TABLE `velos` ADD `angenommen` ENUM('yes','no') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no' AFTER `preis`, ADD INDEX `idx_angenommen` (`angenommen`);
