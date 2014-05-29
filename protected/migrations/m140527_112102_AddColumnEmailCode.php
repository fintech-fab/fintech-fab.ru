<?php

class m140527_112102_AddColumnEmailCode extends CDbMigration
{
	public function up()
	{
		$this->execute("
			ALTER TABLE `tbl_client` ADD `email_code` VARCHAR( 10 ) NOT NULL COMMENT 'Код подтверждения e-mail' AFTER `sms_code`;
			ALTER TABLE `tbl_client` CHANGE `sms_code` `sms_code` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Код SMS-подтверждения';
		");

	}

	public function down()
	{
		echo "m140527_112102_AddColumnEmailCode does not support migration down.\n";

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