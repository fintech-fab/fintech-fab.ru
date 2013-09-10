<?php

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../yii/framework/yii.php';
$config = dirname(__FILE__) . '/../protected/config/main.php';
$debug = dirname(__FILE__) . '/../protected/config/custom/debug.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', $debug['isDebug']);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $debug['debugLevel']);

require_once($yii);
Yii::createWebApplication($config)->run();
