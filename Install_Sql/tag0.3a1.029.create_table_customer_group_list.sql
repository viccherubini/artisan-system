CREATE TABLE `customer_group_list` (
	`group_id` INT( 10 ) NOT NULL ,
	`customer_id` INT( 10 ) NOT NULL ,
	`status` TINYINT( 1 ) NOT NULL DEFAULT '0',
	INDEX ( `group_id` , `customer_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci 