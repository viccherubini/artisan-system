ALTER TABLE `artisan_user` ADD `user_password_salt` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `user_password`;
