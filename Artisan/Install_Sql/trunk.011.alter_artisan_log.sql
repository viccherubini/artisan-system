ALTER TABLE `artisan_log` CHANGE `log_code_id` `code_id` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `log_date` `log_date` DATETIME NOT NULL ,
CHANGE `log_text` `entry` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `log_trace` `trace` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `log_class` `class` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `log_method` `method` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `log_ip` `ip_address` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `log_type` `type` MEDIUMINT( 3 ) NOT NULL;
ALTER TABLE `artisan_log` CHANGE `method` `function` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `artisan_log` CHANGE `trace` `trace` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `class` `class` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `function` `function` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `artisan_log` CHANGE `code_id` `code_id` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;