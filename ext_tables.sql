#
# Table structure for table 'cache_md5params'
#
CREATE TABLE cache_md5params (
	md5hash varchar(20) DEFAULT '' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	type tinyint(3) DEFAULT '0' NOT NULL,
	params text,

	PRIMARY KEY (md5hash)
) ENGINE=InnoDB;
