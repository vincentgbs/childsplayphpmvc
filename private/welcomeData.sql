# This sql dump needs to be run in order for the demo to work

DROP TABLE IF EXISTS `default_name_table`;

CREATE TABLE `default_name_table` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `default_name_table` WRITE;

INSERT INTO `default_name_table` (`id`, `name`)
VALUES
	(1,'World');

UNLOCK TABLES;