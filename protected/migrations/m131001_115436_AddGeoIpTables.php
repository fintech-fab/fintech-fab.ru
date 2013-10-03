<?php
class m131001_115436_AddGeoIpTables extends CDbMigration{	public function up()	{		$this->execute("CREATE TABLE IF NOT EXISTS `tbl_geo__base` (
  `long_ip1` bigint(20) NOT NULL,
  `long_ip2` bigint(20) NOT NULL,
  `ip1` varchar(16) NOT NULL,
  `ip2` varchar(16) NOT NULL,
  `country` varchar(2) NOT NULL,
  `city_id` int(10) NOT NULL,
  KEY `INDEX` (`long_ip1`,`long_ip2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `tbl_geo__cities` (
  `city_id` int(10) NOT NULL,
  `city` varchar(128) NOT NULL,
  `region` varchar(128) NOT NULL,
  `district` varchar(128) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  PRIMARY KEY (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


}


public function down()	{		echo "m131001_115436_AddGeoIpTables does not support migration down.\n";		return false;

$this->execute("");	}

/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

public function safeDown()	{	}	*/}
