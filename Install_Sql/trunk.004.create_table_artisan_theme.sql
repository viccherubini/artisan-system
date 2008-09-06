CREATE TABLE `artisan_theme` (
	`theme_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`theme_name` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`theme_date_added` DATETIME NOT NULL ,
	`theme_status` TINYINT( 1 ) NOT NULL DEFAULT '1',
	PRIMARY KEY ( `theme_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;
