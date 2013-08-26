<?php
class m260821_113950_AddFieldsFullForm extends CDbMigration
{
	public function up()
	{

		Yii::import('admin.components.ImagesComponent');
		ImagesComponent::thumbnailsGenerate();

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
