CREATE TABLE `customer` (`customer_id` INT(10) NOT NULL AUTO_INCREMENT, `birthdate` DATETIME NOT NULL, `gender` CHAR(1) NOT NULL, `firstname` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `lastname` VARCHAR(96) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `fullname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `email_address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `phone` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `phone_ext` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `fax` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `cell` VARCHAR(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `registration_code` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `comments` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, `status` INT(6) NOT NULL DEFAULT '0', PRIMARY KEY (`customer_id`)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `customer` ADD `password` VARCHAR( 96 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `email_address` ,
ADD `password_salt` VARCHAR( 96 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `password`;

ALTER TABLE `customer` ADD UNIQUE (`email_address`);

ALTER TABLE `customer` CHANGE `birthdate` `birthdate` INT( 11 ) NOT NULL DEFAULT '0';

ALTER TABLE `customer` ADD `date_create` INT( 11 ) NOT NULL DEFAULT '0' AFTER `customer_id`;
ALTER TABLE `customer` ADD `date_modify` INT( 11 ) NOT NULL DEFAULT '0' AFTER `date_create`;
