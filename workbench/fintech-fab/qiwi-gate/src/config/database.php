<?php

return array(

	'fetch'       => PDO::FETCH_CLASS,
	'default'     => 'mysql',
	'connections' => array(

		'qiwiGate' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'qiwi_gate',
			'username'  => '',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

	),

);
