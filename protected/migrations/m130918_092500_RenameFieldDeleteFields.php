<?php
class m130918_092500_RenameFieldDeleteFields extends CDbMigration
{
	public function up()
	{

		$this->execute("ALTER TABLE `tbl_client` CHANGE `get_way` `channel_id` TINYINT NOT NULL COMMENT 'Способ получения'");

		$this->execute("ALTER TABLE `tbl_client` DROP `identification_type`, DROP	`flag_processed`, DROP `flag_identified`");

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
