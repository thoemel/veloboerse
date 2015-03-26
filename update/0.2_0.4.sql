-- Haendler_id NULL macht Unterscheidung Händlervelos - Privatvelos schwierig. Darum default 0 setzenb und NULL nicht erlauben.
ALTER TABLE `velos` CHANGE `haendler_id` `haendler_id` INT( 11 ) NOT NULL DEFAULT '0';

