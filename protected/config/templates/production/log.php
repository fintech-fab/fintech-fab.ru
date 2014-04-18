<?php
return array(
	'class'  => 'CLogRouter',
	'routes' => array(
		array(
			'class'  => 'CFileLogRoute',
			'levels' => 'error',
		),
		array(
			'class'    => 'CEmailLogRoute',
			'enabled'  => true,
			'levels'   => 'error',
			'sentFrom' => 'debug@kreddy.ru',
			'subject'  => '[error] Kreddy.ru Error',
			'emails'   => array('debug@kreddy.ru', 'i.popov@fintech-fab.ru', 'a.perepechaev@fintech-fab.ru'),
		),
	)
);
