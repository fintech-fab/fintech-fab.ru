<?php


use FintechFab\QiwiGate\Components\Headers;

Route::filter('ff.qiwi.gate.auth.basic', function () {

	$isSuccess = Headers::CheckAuth();
	if (!$isSuccess) {

		$result = array(
			'response' => array(
				'result_code' => 150,
			),
		);

		return Response::json($result, 401);
	}

});

Route::filter('ff.qiwi.gate.checkUser', function () {
	$user = Config::get('ff-qiwi-shop::user_id');
	if (!isset($user)) {
		dd('В сессии нет пользователя!');
	}

});

