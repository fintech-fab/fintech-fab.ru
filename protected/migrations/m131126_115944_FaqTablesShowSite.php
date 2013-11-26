<?php
class m131126_115944_FaqTablesShowSite extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE  `tbl_faq_groups` ADD  `show_site1` INT( 1 ) NOT NULL DEFAULT  '1' COMMENT  'Показывать на kreddy.ru',
ADD  `show_site2` INT( 1 ) NOT NULL DEFAULT  '1' COMMENT  'Показывать на ivanovo.kreddy.ru' ;

ALTER TABLE  `tbl_faq_questions` ADD  `show_site1` INT( 1 ) NOT NULL DEFAULT  '1' COMMENT  'Показывать на kreddy.ru',
ADD  `show_site2` INT( 1 ) NOT NULL DEFAULT  '1' COMMENT  'Показывать на ivanovo.kreddy.ru' ; ");

		if (Yii::app()->hasComponent('cache')) {
			Yii::app()->getComponent('cache')->flush();
			echo "Cache flused\n";
		}

	}


	public function down()
	{
		echo "m131126_115944_FaqTablesShowSite does not support migration down.\n";

		return false;

		$this->execute("");
	}

	/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

	public function safeDown()	{	}	*/
}
