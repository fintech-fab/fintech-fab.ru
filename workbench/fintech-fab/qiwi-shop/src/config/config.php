<?php

return array(

	'prv_name'      => 'Fintech-Fab',
	'maxSum'   => 5000,
	'minSum'   => 10,
	'user_id'  => Auth::user()->getAuthIdentifier(),
	'lifetime' => 3, //Срок действия заказа в днях
	'appUrl'        => 'http://fintech-fab.dev:8080/qiwi/shop/',
	'qiwiGateUrl'   => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',
	'bill_id'       => 1,
	'Authorization' => '1:password',
);