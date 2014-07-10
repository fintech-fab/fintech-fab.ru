<?php

return array(

	'default'     => 'iron',

	'connections' => array(

		'ff-bank-em'      => array(
			'driver'  => 'iron',
			'project' => '',
			'token'   => '',
			'queue'   => 'ff-bank-em-dev',
			'encrypt' => true,
		),

		'ff-qiwi-gate'    => array(
			'driver'  => 'iron',
			'project' => '',
			'token'   => '',
			'queue'   => 'ff-qiwi-gate',
			'encrypt' => true,
		),

		'ff-actions-calc' => array(
			'driver'  => 'iron',
			'project' => '',
			'token'   => '',
			'queue'   => 'ff-actions-calc',
			'encrypt' => true,
		),

	),

);
