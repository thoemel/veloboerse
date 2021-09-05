CREATE TABLE `probern_boerse`.`rahmennummern` 
( 
`id` INT(11) NOT NULL ,  
`preis` INT(11) NOT NULL ,  
`verkaeufer_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,  
`typ` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,  
`farbe` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,  
`marke` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,  
`rahmennummer` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,    PRIMARY KEY  (`id`))
ENGINE = InnoDB 
COMMENT = 'Für Export zur Polizei';
