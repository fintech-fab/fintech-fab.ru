<?php
/**
 * Файл для запуска yii из консоли
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);
$config=dirname(__FILE__) . '/../protected/config/cron.php';
require_once(dirname(__FILE__) . '/../yii/framework/yii.php');
Yii::createConsoleApplication($config)->run();
