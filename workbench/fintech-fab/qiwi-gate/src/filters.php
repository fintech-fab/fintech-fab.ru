<?php


use FintechFab\QiwiGate\Components\InfoFromHeaders;

Route::filter('ff.qiwi.gate.auth.basic', function () {

	$isSuccess = InfoFromHeaders::CheckAuth();
	if (!$isSuccess) {

		$result = array(
			'response' => array(
				'result_code' => 150,
			),
		);

		return Response::make(json_encode($result), 401);

	}

});

