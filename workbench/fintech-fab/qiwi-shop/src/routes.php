<?php
Route::group(array('before' => 'ff.qiwi.shop.auth'), function () {

	Route::resource(
		'qiwi/shop/orders',
		'FintechFab\QiwiShop\Controllers\RestOrderController'
	);

});


