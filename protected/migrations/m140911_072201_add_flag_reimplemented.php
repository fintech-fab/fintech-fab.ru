<?php

class m140911_072201_add_flag_reimplemented extends CDbMigration
{
	public function up()
	{
		$this->execute("
			ALTER TABLE `tbl_leads_history` ADD COLUMN `flag_reimplemented` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
			COMMENT 'Пиксель был переопределён'
			AFTER `flag_showed`
		");
	}

	public function down()
	{
		$this->execute("
			ALTER TABLE `tbl_leads_history` DROP COLUMN `flag_reimplemented`
		");
	}
}