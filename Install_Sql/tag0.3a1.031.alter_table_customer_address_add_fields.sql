ALTER TABLE `customer_address` ADD `lastname` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `firstname` ,
	ADD `building` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `lastname` ,
	ADD `street_one` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `building` ,
	ADD `street_two` VARCHAR( 64 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `street_one` ,
	ADD `floor` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `street_two` ,
	ADD `office` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `floor` ,
	ADD `postcode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `office` ,
	ADD `region` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `postcode` ,
	ADD `city` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `region` ,
	ADD `country` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `city`;
	
ALTER TABLE `customer_address` ADD `title` VARCHAR( 12 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER `date_modify`;