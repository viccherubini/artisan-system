CREATE TABLE `customer_comment_history` (
`comment_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
`customer_id` INT( 10 ) NOT NULL ,
`subject` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`status` TINYINT( 1 ) NOT NULL DEFAULT '0',
PRIMARY KEY ( `comment_id` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci 

ALTER TABLE `customer_comment_history` ADD `date_added` INT( 11 ) NOT NULL DEFAULT '0' AFTER `customer_id`;

ALTER TABLE `customer_comment_history` ADD INDEX ( `customer_id` );
