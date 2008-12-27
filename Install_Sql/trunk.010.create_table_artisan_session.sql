DROP TABLE IF EXISTS `artisan_session`;
CREATE TABLE IF NOT EXISTS `artisan_session` (
  `session_id` varchar(64) collate utf8_unicode_ci NOT NULL,
  `session_expiration_time` int(11) NOT NULL default '0',
  `session_ip` varchar(20) collate utf8_unicode_ci NOT NULL,
  `session_user_agent` varchar(255) collate utf8_unicode_ci NOT NULL,
  `session_user_agent_hash` text collate utf8_unicode_ci NOT NULL,
  `session_data` blob NOT NULL,
  PRIMARY KEY  (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
