<?php


return array(

	'session' => array(
		'cookieParams' => array(
			'domain'   => '.dev.kreddy.popov',
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