<?php
class m131126_155446_FaqTablesFillWithData extends CDbMigration
{
	public function up()
	{

		$sQuery = file_get_contents('FAQTablesData.sql');
		$this->execute($sQuery);

		if (Yii::app()->hasComponent('cache')) {
			Yii::app()->getComponent('cache')->flush();
			echo "Cache flused\n";
		}

	}


	public function down()
	{
		echo "m131126_155446_FaqTablesFillWithData does not support migration down.\n";

		return false;

		$this->execute("");
	}

	/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

	public function safeDown()	{	}	*/
}
