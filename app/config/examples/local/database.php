<?php

return array(

	'connections' => array(

		// основной коннект к базе данных
		'mysql'        => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => '',
			'username'  => '',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_general_ci',
			'prefix'    => '',
		),

		// для пакетов эмулятора платежного гейта
		'ff-qiwi-gate' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => '',
			'username'  => '',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		// для пакетов эмулятора интернет-магазина
		'ff-qiwi-shop' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => '',
			'username'  => '',
			'password'  => '',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

		// для пакета эмулятора банка
		'ff-bank-em'   => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'bank',
			'username'  => 'root',
			'password'  => 'e,eyne',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),

	),

);
