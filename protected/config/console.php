<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$a = array(
	'basePath'   => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'       => 'My Console Application',

	// preloading 'log' component
	'preload'    => array('log'),


	// application components
	'components' => array(
		'db'           => array(
			'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'assetManager' => array(
			'class'    => 'CAssetManager',
			'basePath' => realpath(__DIR__ . '/../../public/assets'),
			'baseUrl'  => '/assets',
		),

		'log'          => array(
			'class'  => 'CLogRouter',
			'routes' => array(
				array(
					'class'  => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),
	),
);

$a['components'] = CMap::mergeArray($a['components'], require(__DIR__ . '/custom/db.php'));

return $a;