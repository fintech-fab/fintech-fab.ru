<?php
namespace FintechFab\QiwiShop\Components;


class CurlMaker
{

	public static function getConfig($key)
	{
		$keyArray = explode('.', $key);
		$config = array(
			'gate_url' => 'http://fintech-fab.dev/qiwi/gate/api/v2/prv/',
			'provider' => array(
				'name'     => 'Fintech-Fab',
				'id'       => '1',
				'password' => '1234',
			),
		);

		for ($i = 0; $i < count($keyArray); $i++) {
			$config = $config[$keyArray[$i]];
		}

		return $config;

	}

	/**
	 * @param int    $order_id
	 * @param string $method
	 * @param null   $query
	 * @param null   $url
	 *
	 * @return mixed
	 */
	public static function makeCurl($order_id, $method = 'GET', $query = null, $url = null)
	{
		if ($url == null) {
			$url = self::getConfig('gate_url') . self::getConfig('provider.id') .
				'/bills/' . $order_id;
		}

		$headers = array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt(
			$ch,
			CURLOPT_USERPWD,
			self::getConfig('provider.id') . ':' . self::getConfig('provider.password')
		);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($query != null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
		}

		$httpResponse = curl_exec($ch);
		$httpError = curl_error($ch);
		$info = curl_getinfo($ch);
		$response = @json_decode($httpResponse);

		if (!$response || !$httpResponse || $httpError) {

			$aResponse = array(
				'url'      => $url,
				'code'     => $info['http_code'],
				'error'    => $httpError,
				'response' => $httpResponse,
			);

			echo json_encode($aResponse);
			die();
		}


		return $response;
	}
} 