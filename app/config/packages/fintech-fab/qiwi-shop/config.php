<?php

return array(

	'provider' => array(
		'name'     => 'Fintech-Fab',
		'id'       => '1',
		'password' => '1234',
	),

	'user_id'  => 1, //Auth::user() ? Auth::user()->getAuthIdentifier() : null,

	'lifetime' => 3, // Срок действия заказа в днях

	'gateUrl'  => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',

);