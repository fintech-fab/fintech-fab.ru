<?php


/**
 * этот конфиг не используется,
 * вам необходимо добавить коннект к базе в ваш локальный конфиг database
 */
return array(

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
