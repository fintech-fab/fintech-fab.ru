<?php
namespace FintechFab\QiwiShop\Components;


class Curl
{


	public static function  setUrl($orderId, $payReturnId)
	{
		$url = QiwiGateConnector::getConfig('gate_url') . QiwiGateConnector::getConfig('provider.id')
			. '/bills/' . $orderId;
		if ($payReturnId != null) {
			$url .= '/refund/' . $payReturnId;
		}

		return $url;
	}

	/**
	 * @param int    $order_id
	 * @param string $method
	 * @param null   $query
	 * @param null   $payReturnId
	 *
	 * @return mixed
	 */
	public static function makeCurl($order_id, $method = 'GET', $query = null, $payReturnId = null)
	{
		$url = self::setUrl($order_id, $payReturnId);

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
			QiwiGateConnector::getConfig('provider.id') . ':'
			. QiwiGateConnector::getConfig('provider.password')
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