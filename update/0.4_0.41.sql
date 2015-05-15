-- Wird ueber PHP Sessions gemacht.
DROP TABLE ci_sessions;

-- Neues Feld fuer Velafrika
ALTER TABLE `velos` ADD `afrika` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `problemfall` ;
