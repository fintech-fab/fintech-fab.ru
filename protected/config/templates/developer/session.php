<?php


return array(
	'session' => array(
		'cookieParams' => array(
			'domain'   => '.%MAIN_URL%',
			'httponly' => false,
			'secure'   => false
		)
	),
	'request' => array(
		'csrfCookie' => array(
			'httpOnly' => false,
			'secure'   => false,
		),
	),
);