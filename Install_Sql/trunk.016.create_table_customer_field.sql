CREATE TABLE `customer_field` (
	`field_id` TINYINT( 2 ) NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`status` TINYINT( 2 ) NOT NULL DEFAULT '1',
	PRIMARY KEY ( `field_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `customer_field` ADD `type_id` TINYINT( 2 ) NOT NULL AFTER `field_id` ;
ALTER TABLE `customer_field` ADD INDEX ( `type_id` ) ;