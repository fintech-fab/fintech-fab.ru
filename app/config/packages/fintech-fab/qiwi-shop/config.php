<?php

return array(

	'provider' => array(
		'name'     => 'Fintech-Fab',
		'id'       => '2',
		'password' => '1234',
	),

	'user_id'  => Auth::user()->getAuthIdentifier(),

	// Срок действия заказа в днях
	'lifetime' => 3,

	'gate_url' => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',

);