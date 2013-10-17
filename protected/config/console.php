<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
$a = array(
	'basePath'       => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name'           => 'My Console Application',

	'sourceLanguage' => 'ru',
	'language'       => 'ru',

	// preloading 'log' component
	'preload'        => array(
		'log'
	),


	'modules'        => array(),

	'import'         => array(
		'application.extensions.image.*',
	),

	// application components
	'components'     => array(
		// uncomment the following to use a MySQL database

		'db'           => array(
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
$a['modules'] = CMap::mergeArray($a['modules'], require(__DIR__ . '/custom/modules.php'));

return $a;