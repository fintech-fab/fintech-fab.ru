<?php

return array(

	'provider' => array(
		'name'     => 'Fintech-Fab',
		'id'       => '',
		'password' => '',
	),

	'user_id'  => Auth::user() ? Auth::user()->getAuthIdentifier() : null,

	// Срок действия заказа в днях
	'lifetime' => 3,

	'gateUrl'  => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',

);