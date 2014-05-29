<?php
Route::filter('ff.qiwi.shop.checkUser', function () {
	$user = Config::get('ff-qiwi-shop::user_id');
	if (!isset($user)) {
		return Redirect::route('shopAuthError');
	}

});