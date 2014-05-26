<?php

return array(
	'user_id' => Auth::user() ? Auth::user()->getAuthIdentifier() : null,
	'ff'      => array(
		'login' => array(
			'enabled' => true,
			'link'    => '/registration',
		),
	),
);