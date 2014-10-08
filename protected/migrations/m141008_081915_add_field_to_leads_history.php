<?php

class m141008_081915_add_field_to_leads_history extends CDbMigration
{
	public function up()
	{
		$this->execute("
			ALTER TABLE `tbl_leads_history` ADD COLUMN `webmaster_id` VARCHAR(255) NOT NULL DEFAULT ''
			COMMENT 'ID вебмастера (sub_id)'
			AFTER `lead_name`
		");
	}

	public function down()
	{
		$this->execute("
			ALTER TABLE `tbl_leads_history` DROP COLUMN `webmaster_id`
		");
	}

}