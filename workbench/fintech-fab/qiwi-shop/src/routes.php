<?php
Route::group(array('before' => 'ff.qiwi.shop.auth'), function () {
	Route::get('fintech-fab/qiwi-shop/order', array(
		'as'   => 'order',
		'uses' => 'FintechFab\QiwiShop\Controllers\OrderController@createOrder'
	));
	Route::get('fintech-fab/qiwi-shop/table', array(
		'as'   => 'order',
		'uses' => 'FintechFab\QiwiShop\Controllers\OrderController@ordersTable'
	));
});

