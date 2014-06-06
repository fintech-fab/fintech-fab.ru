<?php

/**
 * Class m140602_072312_AddTokenColumnToClients
 */
class m140602_072312_AddTokenColumnToClients extends CDbMigration
{
	/**
	 * @return bool|void
	 */
	public function up()
	{
		$this->execute("
		ALTER TABLE `tbl_client` ADD `api_token` VARCHAR( 255 ) NOT NULL COMMENT 'Токен для API' AFTER `complete`;
		");

	}

	/**
	 * @return bool
	 */
	public function down()
	{
		echo "m140602_072312_AddTokenColumnToClients does not support migration down.\n";

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