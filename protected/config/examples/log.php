<?php
return array(
	'class'  => 'CLogRouter',
	'routes' => array(
		array(
			'class'  => 'CFileLogRoute',
			'levels' => 'error, warning',
		),
		array(
			'class'        => 'CDbLogRoute',
			'levels'       => 'error, warning',
			'connectionID' => 'db',
			'logTableName' => 'tbl_log',

		),
	)
);