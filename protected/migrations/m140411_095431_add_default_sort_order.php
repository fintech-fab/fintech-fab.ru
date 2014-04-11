<?php

class m140411_095431_add_default_sort_order extends CDbMigration
{
	public function up()
	{
		$this->execute("
			ALTER TABLE `tbl_faq_groups` CHANGE `sort_order` `sort_order` INT( 11 ) NOT NULL DEFAULT '999999999';
			ALTER TABLE `tbl_faq_questions` CHANGE `sort_order` `sort_order` INT( 11 ) NOT NULL DEFAULT '999999999';
			ALTER TABLE `tbl_footer_links` CHANGE `link_order` `link_order` INT( 11 ) NOT NULL DEFAULT '999999999';

		 ");
	}

	public function down()
	{
		echo "m140411_095431_add_default_sort_order does not support migration down.\n";

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