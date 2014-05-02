<?php

Route::group(array('before' => 'ff.qiwi.gate.auth.basic'), function () {


	Route::resource(
		'qiwi/gate/api/v2/prv/{provider_id}/bills',
		'FintechFab\QiwiGate\Controllers\RestBillController',
		array(
			'only' => array('show', 'update')
		)
	);

	Route::resource(
		'qiwi/gate/api/v2/prv/{provider_id}/bills/{bill_id}/refund',
		'FintechFab\QiwiGate\Controllers\RestRefundController',
		array(
			'only' => array('show', 'update')
		)
	);

});

Route::get('qiwi/gate/order/external/main.action', array(
	'as'   => 'index',
	'uses' => 'FintechFab\QiwiGate\Controllers\PayController@index'
));

Route::get('qiwi/gate/order/external/main.action/payResult', array(
	'as'   => 'payResult',
	'uses' => 'FintechFab\QiwiGate\Controllers\PayController@payResult'
));

Route::post('qiwi/gate/order/external/main.action', array(
	'as'   => 'postPay',
	'uses' => 'FintechFab\QiwiGate\Controllers\PayController@postPay'
));

