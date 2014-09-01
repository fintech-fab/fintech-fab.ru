<?php

class m140901_085533_add_leads_history_table extends CDbMigration
{
	public function up()
	{
		$this->execute("
			CREATE TABLE IF NOT EXISTS `tbl_leads_history` (
		          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
		          `lead_name` varchar(255) NOT NULL COMMENT 'Название лида',
		          `parent_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Предыдущий ID',
		          `first_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Первый ID для текущего клиента',
		          `flag_showed` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Было ли отображение пикселя',
		          `dt_add` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата добавления',
		          `dt_show` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата отображения пикселя',
		          PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
		");
	}

	public function down()
	{
		return false;
	}

}