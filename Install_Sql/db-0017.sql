CREATE TABLE `customer_field_type` (
	`type_id` TINYINT( 2 ) NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`maxlength` INT( 6 ) NOT NULL ,
	`valid_regex` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
	`hook` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
PRIMARY KEY ( `type_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci 