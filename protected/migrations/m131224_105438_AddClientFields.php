<?php
class m131224_105438_AddClientFields extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_client` ADD `status` TINYINT UNSIGNED NOT NULL COMMENT 'Трудовой статус' AFTER `friends_phone` ,
ADD `income_source` VARCHAR( 255 ) NOT NULL COMMENT 'Источник дохода' AFTER `status` ,
ADD `educational_institution_name` VARCHAR( 255 ) NOT NULL COMMENT 'Название учебного заведения' AFTER `income_source` ,
ADD `educational_institution_phone` VARCHAR( 10 ) NOT NULL COMMENT 'Номер телефона учебного заведения' AFTER `educational_institution_name` ,
ADD `loan_purpose` TINYINT UNSIGNED NOT NULL COMMENT 'Цель займа' AFTER `educational_institution_phone` ;");


	}


	public function down()
	{
		echo "m131224_105438_AddClientFields does not support migration down.\n";

		return false;

		$this->execute("");
	}

	/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

	public function safeDown()	{	}	*/
}
