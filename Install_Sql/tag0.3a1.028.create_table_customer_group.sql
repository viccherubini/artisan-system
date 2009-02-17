CREATE TABLE `customer_group` (
	`group_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`status` TINYINT( 1 ) NOT NULL DEFAULT '0',
	PRIMARY KEY ( `group_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;