<?php

//Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../extensions/bootstrap');

$a = array(
	'basePath'       => __DIR__ . '/..',
	'name'           => 'Kreddy',

	'sourceLanguage' => 'ru',
	'language'       => 'ru',

	'preload'        => array(
		'log',
	),

	'import'         => array(
		'application.models.*',
		'application.controllers.*',
		'application.components.*',
	),

	'modules'        => array(
		'admin'   => array(
			'ipFilters' => array('127.0.0.1', '::1'),
		),
		'account' => array(),
	),

	'params'         => array(),


	'components'     => array(
		'db'               => array(
			'connectionString' => 'mysql:host=localhost;dbname=kreddy',
			'emulatePrepare'   => true,
			'username'         => 'kreddy',
			'password'         => '159753',
			'charset'          => 'utf8',
		),
		'assetManager' => array(
			'class'    => 'CAssetManager',
			'basePath' => realpath(__DIR__ . '/../../public/assets'),
			'baseUrl'  => '/assets',
		),
	),

);

$a['components'] = CMap::mergeArray($a['components'], require(__DIR__ . '/custom/db.php'));

return $a;
