<?php

class m140507_091142_add_pay_type_column extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_client` ADD `pay_type` TINYINT NOT NULL DEFAULT '3' COMMENT 'Тип оплаты (пред/пост)' AFTER `product` ;");

	}

	public function down()
	{
		echo "m140507_091142_add_pay_type_column does not support migration down.\n";

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