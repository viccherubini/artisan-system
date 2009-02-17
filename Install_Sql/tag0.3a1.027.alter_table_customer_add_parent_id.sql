ALTER TABLE `customer` ADD `parent_id` INT( 10 ) NOT NULL AFTER `customer_id`;
ALTER TABLE `customer` ADD INDEX ( `parent_id` );