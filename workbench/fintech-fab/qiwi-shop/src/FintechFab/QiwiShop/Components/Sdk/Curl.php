<?php
namespace FintechFab\QiwiShop\Components\Sdk;


class Curl
{
	public $url;
	public $curlError;

	/**
	 * @param $orderId
	 * @param $payReturnId
	 *
	 */
	public function  setUrl($orderId, $payReturnId = null)
	{
		$this->url = QiwiGateConnector::getConfig('gate_url') . QiwiGateConnector::getConfig('provider.id')
			. '/bills/' . $orderId;
		if ($payReturnId != null) {
			$this->url .= '/refund/' . $payReturnId;
		}

	}

	/**
	 * @param int    $order_id
	 * @param string $method
	 * @param null   $query
	 * @param null   $payReturnId
	 *
	 * @return mixed
	 */
	public function makeCurl($order_id, $method = 'GET', $query = null, $payReturnId = null)
	{
		$this->setUrl($order_id, $payReturnId);

		$headers = array(
			"Accept: text/json",
			"Content-Type: application/x-www-form-urlencoded; charset=utf-8",
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
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

			$this->curlError = array(
				'code'     => $info['http_code'],
				'error'    => $httpError,
				'response' => $httpResponse,
			);

			return ($this->curlError);
		}

		return $response;
	}
} 