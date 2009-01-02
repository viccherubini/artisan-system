CREATE TABLE `customer_history` (
	`history_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`date_create` INT( 11 ) NOT NULL DEFAULT '0',
	`revision` INT( 6 ) NOT NULL DEFAULT '1',
	`field` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`value` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	PRIMARY KEY ( `history_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;
