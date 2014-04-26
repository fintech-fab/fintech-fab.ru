<?php
Route::group(array('before' => 'ff.qiwi.shop.auth'), function () {

	Route::resource(
		'qiwi/shop/{user_id}/orders',
		'FintechFab\QiwiShop\Controllers\RestOrderController'
	);

});


