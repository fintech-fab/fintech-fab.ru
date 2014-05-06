<?php

return array(

	'vk' => array(
		'ID'           => '',
		'key'          => '',
		'redirect_url' => 'http://fintech-fab.dev/vk',
	),

	'fb' => array(
		'ID'           => '',
		'key'          => '',
		'redirect_url' => 'http://fintech-fab.dev/fb',
	),

	'gp' => array(
		'ID'           => null,
		'key'          => null,
		'redirect_url' => 'http://fintech-fab.dev/gp',
		'url'          => 'https://accounts.google.com/o/oauth2/auth',
		'scope'        => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
	),
);
