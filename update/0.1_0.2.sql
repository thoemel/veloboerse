-- Fuer Haendlerformular
ALTER TABLE `haendler` ADD `code` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Fuer Direktlink' AFTER `id` ;

UPDATE `haendler` SET code = uuid( ) ;

ALTER TABLE `probern_boerse`.`haendler` ADD UNIQUE `idx_code` ( `code` ) COMMENT '';


ALTER TABLE `velos` ADD `typ` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `farbe` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `marke` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `rahmennummer` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
ADD `vignettennummer` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ;
