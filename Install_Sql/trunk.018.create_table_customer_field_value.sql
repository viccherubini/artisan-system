CREATE TABLE `customer_field_value` (
	`value_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`customer_id` INT( 10 ) NOT NULL ,
	`field_id` TINYINT( 2 ) NOT NULL ,
	`value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	PRIMARY KEY ( `value_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `customer_field_value` ADD INDEX ( `customer_id`, `field_id` ) ;