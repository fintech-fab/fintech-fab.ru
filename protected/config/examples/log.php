<?php
return array(
	'class'  => 'CLogRouter',
	'routes' => array(
		array(
			'class'  => 'CFileLogRoute',
			'levels' => 'error, warning',
		)
	)
);