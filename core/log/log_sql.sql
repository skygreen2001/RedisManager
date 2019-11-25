DROP TABLE IF EXISTS `bb_log_log`;
CREATE TABLE `bb_log_log` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ident` char(16) DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `message` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;