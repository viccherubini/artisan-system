CREATE TABLE `artisan_log` (
	`log_id` INT( 11 ) NOT NULL AUTO_INCREMENT,
	`log_code_id` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`log_date` DATETIME NOT NULL,
	`log_text` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`log_trace` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`log_class` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`log_function` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`log_ip` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`log_type` CHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY ( `log_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;
