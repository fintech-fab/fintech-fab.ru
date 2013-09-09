<?php
return array(
	'class'  => 'CLogRouter',
	'routes' => array(
		array(
			'class'  => 'CFileLogRoute',
			'levels' => 'error, warning, trace',
		),
		array(
			'class'         => 'CWebLogRoute',
			'categories'    => 'application',
			'levels'        => 'error, warning, trace, profile, info',
			'showInFireBug' => true
		),
	)
);