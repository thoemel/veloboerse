-- Fuer Haendlerformular
ALTER TABLE `haendler` ADD `code` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Fuer Direktlink' AFTER `id` ;
UPDATE `haendler` SET code = uuid( ) ;
ALTER TABLE `probern_boerse`.`haendler` ADD UNIQUE `idx_code` ( `code` );


ALTER TABLE `velos` ADD `typ` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `farbe` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `marke` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `rahmennummer` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `vignettennummer` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;


-- Diverse neue Felder
ALTER TABLE `velos` ADD `gestohlen` BOOLEAN NOT NULL DEFAULT FALSE ,
ADD `storniert` BOOLEAN NOT NULL DEFAULT FALSE ,
ADD `problemfall` BOOLEAN NOT NULL DEFAULT FALSE ,
ADD `bemerkungen` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;


-- Plus Kreditkarte
ALTER TABLE `velos` CHANGE `zahlungsart` `zahlungsart` ENUM( 'bar', 'karte', 'kredit', 'debit' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;
UPDATE `velos` SET `zahlungsart` = 'debit' WHERE `zahlungsart` = 'karte';
ALTER TABLE `velos` CHANGE `zahlungsart` `zahlungsart` ENUM( 'bar', 'kredit', 'debit' ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ;

