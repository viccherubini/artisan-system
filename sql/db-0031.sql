CREATE TABLE `customer_field_address` (
	`field_id` tinyint( 2 ) NOT NULL AUTO_INCREMENT ,
	`type_id` tinyint( 2 ) NOT NULL ,
	`name` varchar( 64 ) COLLATE utf8_unicode_ci NOT NULL ,
	`status` tinyint( 2 ) NOT NULL default '1',
	PRIMARY KEY ( `field_id` ) ,
	UNIQUE KEY `name` ( `name` ) ,
	KEY `type_id` ( `type_id` )
) ENGINE = MYISAM DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;