<?php

/**
 * Class FileCleanerCommand
 */
class FileCleanerCommand extends CConsoleCommand
{
	public function actionClearOldFiles()
	{
		echo shell_exec("find " . Yii::app()->params['sDocumentsPath'] . " -type f -newermt '1 hours ago' -exec rm {} \;");
	}
}