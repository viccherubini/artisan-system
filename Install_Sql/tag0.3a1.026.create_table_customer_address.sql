CREATE TABLE `customer_address` (
	`address_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`customer_id` INT( 10 ) NOT NULL ,
	`date_create` INT( 11 ) NOT NULL ,
	`date_modify` INT( 11 ) NOT NULL ,
	`status` TINYINT( 1 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `address_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;