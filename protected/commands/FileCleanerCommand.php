<?php

/**
 * Class FileCleanerCommand
 */
class FileCleanerCommand extends CConsoleCommand
{
	public function actionClearOldFiles()
	{
		//Удаляем все файлы из папки, которые старше 120 минут
		shell_exec('find ' . Yii::app()->params['sDocumentsPath'] . ' -type f -mmin +120 -delete');
	}
}