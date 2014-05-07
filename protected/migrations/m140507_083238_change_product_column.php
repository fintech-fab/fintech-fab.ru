<?php

class m140507_083238_change_product_column extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_client` CHANGE `product` `product` VARCHAR( 20 ) NOT NULL DEFAULT '' COMMENT 'Продукт';");
	}

	public function down()
	{
		echo "m140507_083238_change_product_column does not support migration down.\n";

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