<?php
class m131025_105904_AdminAddNewFields extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_footer_links` ADD `show_site1` TINYINT( 1 ) NOT NULL COMMENT 'Показывать на kreddy.ru',
ADD `show_site2` TINYINT( 1 ) NOT NULL COMMENT 'Показывать на ivanovo.kreddy.ru';

ALTER TABLE `tbl_bottom_tabs` ADD `show_site1` TINYINT( 1 ) NOT NULL COMMENT 'Показывать на kreddy.ru',
ADD `show_site2` TINYINT( 1 ) NOT NULL COMMENT 'Показывать на ivanovo.kreddy.ru';");


		$this->clearAssets();
	}

	private function clearAssets()
	{
		$path = Yii::app()->assetManager->getBasePath();
		$this->clearDir($path);
		echo "Assets clear\n";
	}

	private function clearDir($folder, $main = true)
	{
		if (is_dir($folder)) {
			$handle = opendir($folder);
			while ($subfile = readdir($handle)) {
				if ($subfile == '.' || $subfile == '..' || $subfile == '.gitignore') {
					continue;
				}
				if (is_file($subfile)) {
					unlink("{$folder}/{$subfile}");
				} else {
					$this->clearDir("{$folder}/{$subfile}", false);
				}
			}
			closedir($handle);
			if (!$main) {
				rmdir($folder);
			}
		} else {
			unlink($folder);
		}
	}

	public function down()
	{
		echo "m131025_105904_AdminAddNewFields does not support migration down.\n";

		return false;

		$this->execute("");
	}

	/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

	public function safeDown()	{	}	*/
}
