<?php
Route::group(array(
	'before'    => 'ff.qiwi.shop.auth',
	'prefix'    => 'qiwi/shop/orders',
	'namespace' => 'FintechFab\QiwiShop\Controllers'
), function () {

	Route::get('/', array(
		'as'   => 'ordersTable',
		'uses' => 'OrderController@ordersTable',
	));
	Route::get('/create', array(
		'as'   => 'createOrder',
		'uses' => 'OrderController@createOrder',
	));
	Route::post('/create', array(
		'as'   => 'createOrder',
		'uses' => 'OrderController@postCreateOrder',
	));
	Route::post('/{action}', array(
		'as'   => 'createOrder',
		'uses' => 'OrderController@getAction',
	));

});


