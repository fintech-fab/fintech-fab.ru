<?php
class m130904_173000_FixNumericCodeType extends CDbMigration
{
	public function up()
	{

		$this->execute("ALTER TABLE `tbl_client` ADD `flag_cleared` TINYINT( 1 ) NOT NULL COMMENT 'Запись очищена'");

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
