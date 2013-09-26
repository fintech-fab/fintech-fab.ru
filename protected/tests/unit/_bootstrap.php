<?php
// Here you can initialize variables that will for your tests
// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../yii/framework/yiit.php';
$config=dirname(__FILE__).'/../../config/test.php';

require_once($yiit);
//require_once(dirname(__FILE__).'/WebTestCase.php');

if(!Yii::app()) Yii::createWebApplication($config);
