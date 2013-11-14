<?php
class m131114_095300_AddFieldsFlexProduct extends CDbMigration
{
	public function up()
	{
		$this->execute("ALTER TABLE `tbl_client` ADD `entry_point` TINYINT( 1 ) NOT NULL COMMENT 'Точка входа' AFTER `ip`;

ALTER TABLE `tbl_client` ADD `flex_amount` INT NOT NULL COMMENT 'Сумма гибкого займа' AFTER `channel_id` ,
ADD `flex_time` TINYINT( 2 ) NOT NULL COMMENT 'Время гибкого займа (в днях)' AFTER `flex_amount` ;
ALTER TABLE `tbl_client` CHANGE `channel_id` `channel_id` VARCHAR( 20 ) NOT NULL COMMENT 'Способ получения'
");

		if (Yii::app()->hasComponent('cache')) {
			Yii::app()->getComponent('cache')->flush();
			echo "Cache flused\n";
		}

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
		echo "m131114_095300_AddFieldsFlexProduct does not support migration down.\n";

		return false;

		$this->execute("");
	}

	/*	// Use safeUp/safeDown to do migration with transaction	public function safeUp()	{	}

	public function safeDown()	{	}	*/
}
