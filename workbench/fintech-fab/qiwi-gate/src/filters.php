<?php


use FintechFab\QiwiGate\Models\Merchant;

Route::filter('ff.qiwi.gate.auth.basic', function () {

	$isSuccess = false;

	$authBasicHeader = trim(Request::header('Authorization'));
	if ($authBasicHeader) {

		preg_match('/^Basic (.+)$/', $authBasicHeader, $matches);
		@list($login, $password) = explode(':', base64_decode($matches[1]));

		if ($login && $password) {

			$merchant = Merchant::find($login);
			if ($merchant && $merchant->password == $password) {
				$isSuccess = true;
			}

		}

	}

	if (!$isSuccess) {

		$result = array(
			'response' => array(
				'result_code' => 150,
			),
		);

		return Response::make(json_encode($result), 401);

	}

});

