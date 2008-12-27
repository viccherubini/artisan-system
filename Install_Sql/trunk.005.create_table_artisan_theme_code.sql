CREATE TABLE `artisan_theme_code` (
	`theme_code_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`theme_id` INT( 10 ) NOT NULL ,
	`code_name` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	`code` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
	PRIMARY KEY ( `theme_code_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;
