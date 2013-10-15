<?php
class m131015_092822_ChangeFooterLinkTitleSize extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_footer_links` CHANGE `link_title` `link_title` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ");


	}


	public function down()
	{
		echo "m131015_092822_ChangeFooterLinkTitleSize does not support migration down.\n";

		return false;

		$this->execute("");
	}

	/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

	public function safeDown()	{	}	*/
}
