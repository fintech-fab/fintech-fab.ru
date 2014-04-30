<?php

return array(

	'prv_name' => 'Fintech-Fab',
	'maxSum'   => 5000,
	'minSum'   => 10,
	'user_id'  => Auth::user()->getAuthIdentifier(),
	'lifetime' => 3, //Срок действия заказа в днях

	'qiwiGateUrl'   => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',

	'GateAuth' => array(
		'merchant_id' => '1',
		'password'    => 'password',
	),
);