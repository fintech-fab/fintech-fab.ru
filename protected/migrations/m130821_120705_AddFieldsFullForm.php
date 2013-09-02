<?php
class m130821_120705_AddFieldsFullForm extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_client` ADD `password` VARCHAR( 100 ) NOT NULL COMMENT 'Пароль' AFTER `phone` ,
ADD `address_res_region` TINYINT( 3 ) NOT NULL COMMENT 'Регион проживания' AFTER `address_reg_address` ,
ADD `address_res_city` VARCHAR( 100 ) NOT NULL COMMENT 'Город проживания' AFTER `address_res_region` ,
ADD `address_res_address` VARCHAR( 255 ) NOT NULL COMMENT 'Адрес проживания' AFTER `address_res_city` ,
ADD `address_reg_as_res` TINYINT( 1 ) NOT NULL COMMENT 'Место проживания совпадает с пропиской' AFTER `address_res_address` ");

		$this->execute("ALTER TABLE `tbl_client` CHANGE `sex` `sex` TINYINT( 1 ) NOT NULL COMMENT 'Пол'");

		if (Yii::app()->hasComponent('cache')) {
			Yii::app()->getComponent('cache')->flush();
			echo "Cache flushed\n";
		}
	}

	public function down()
	{
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
