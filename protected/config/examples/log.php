<?php
return array(
	'class'  => 'CLogRouter',
	'routes' => array(
		array(
			'class'  => 'CFileLogRoute',
			'levels' => 'error, warning',
		),
		array(
			'class'         => 'CWebLogRoute',
			'categories'    => 'application',
			'levels'        => 'error, warning, trace, profile, info',
			'showInFireBug' => true
		),
		array(
			'class'        => 'CDbLogRoute',
			'levels'       => 'error, warning, trace',
			'connectionID' => 'db',
			'logTableName' => 'tbl_log',

		),
	)
);