<?php
return array(
	'gii' => array(
		'generatorPaths' => array(
			'bootstrap.gii',
		),
		'class' => 'system.gii.GiiModule',
		'password' => '111',
		'ipFilters' => array('127.0.0.1', '::1'),
	),
	'admin' => array(
		'ipFilters' => array('127.0.0.1', '::1'),
	),
);