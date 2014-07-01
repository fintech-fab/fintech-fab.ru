<?php

return array(
	'vk' => array(
		'ID'           => null,
		'key'          => null,
		'redirect_url' => 'http://fintech-fab.dev:8000/vk',
		'url'          => 'http://oauth.vk.com/authorize',
	),
	'fb' => array(
		//Для Facebook
		'ID'           => null,
		'key'          => null,
		'redirect_url' => 'http://fintech-fab.dev:8000/fb',
		'url'          => 'https://www.facebook.com/dialog/oauth',
	),
	'gp' => array(
		//Для Google+
		'ID'           => null,
		'key'          => null,
		'redirect_url' => 'http://localhost/fintech-fab.dev:8000/gp',
		'url'          => 'https://accounts.google.com/o/oauth2/auth',
		'scope'        => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'
	),
);