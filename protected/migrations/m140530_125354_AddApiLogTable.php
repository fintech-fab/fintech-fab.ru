<?php

/**
 * Class m140530_125354_AddApiLogTable
 */
class m140530_125354_AddApiLogTable extends CDbMigration
{
	/**
	 * @return bool|void
	 */
	public function up()
	{
		$this->execute("

		CREATE TABLE IF NOT EXISTS `tbl_api_log` (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
          `controller` varchar(255) NOT NULL COMMENT 'Контроллер',
          `action` varchar(255) NOT NULL COMMENT 'Действие',
          `path` varchar(255) NOT NULL COMMENT 'Путь URI',
          `request` text NOT NULL COMMENT 'Данные запроса',
          `response_code` int(11) NOT NULL COMMENT 'Код ответа',
          `response` text NOT NULL COMMENT 'Данные ответа',
          `dt_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата добавления',
          PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;
		");
	}

	/**
	 * @return bool
	 */
	public function down()
	{
		echo "m140530_125354_AddApiLogTable does not support migration down.\n";

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