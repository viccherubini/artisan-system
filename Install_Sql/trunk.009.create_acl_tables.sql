DROP TABLE IF EXISTS `artisan_acl_action`;
CREATE TABLE IF NOT EXISTS `artisan_acl_action` (
  `action_id` int(5) NOT NULL auto_increment,
  `action_name` varchar(64) collate utf8_unicode_ci NOT NULL,
  `action_view` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`action_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `artisan_acl_group`;
CREATE TABLE IF NOT EXISTS `artisan_acl_group` (
  `group_id` int(5) NOT NULL auto_increment,
  `group_name` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `artisan_acl_group_member`;
CREATE TABLE IF NOT EXISTS `artisan_acl_group_member` (
  `group_id` int(5) NOT NULL,
  `user_id` int(10) NOT NULL,
  `date_added` int(11) NOT NULL,
  KEY `group_id` (`group_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `artisan_acl_permission`;
CREATE TABLE IF NOT EXISTS `artisan_acl_permission` (
  `action_id` int(5) NOT NULL,
  `group_id` int(5) NOT NULL,
  `permission_level` tinyint(1) NOT NULL default '0',
  KEY `action_id` (`action_id`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
