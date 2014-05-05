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

Route::group(array(
	'before'    => 'ff.qiwi.gate.checkUser',
	'prefix'    => 'qiwi/gate',
	'namespace' => 'FintechFab\QiwiGate\Controllers'
), function () {
	Route::get('order/external/main.action', array(
		'as'   => 'payIndex',
		'uses' => 'PayController@index'
	));

	Route::post('order/external/main.action', array(
		'as'   => 'postPay',
		'uses' => 'PayController@postPay'
	));

	Route::get('account', array(
		'as'   => 'accountIndex',
		'uses' => 'Account\AccountController@index'
	));

	Route::post('account', array(
		'as'   => 'postAccountReg',
		'uses' => 'Account\AccountController@postRegistration'
	));

	Route::post('account/changeData', array(
		'as'   => 'postChangeData',
		'uses' => 'Account\AccountController@postChangeData'
	));

});

