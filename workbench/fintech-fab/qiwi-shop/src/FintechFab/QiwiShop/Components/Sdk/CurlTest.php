<?php
namespace FintechFab\QiwiShop\Components\Sdk;


class CurlTest
{

	/**
	 * @param int $order_id
	 *
	 * @internal param string $method
	 * @internal param null $query
	 * @internal param null $payReturnId
	 *
	 * @return mixed
	 */
	public function makeCurl($order_id)
	{

		$data = array(
			'response' => array(
				'result_code' => 0,
				'bill'        => array(
					'bill_id' => $order_id,
					'status'  => 'waiting',
				),
				'refund'      => array(
					'id'     => 1,
					'amount' => 15,
					'status' => 'processing',
				),
			)
		);
		$response = json_encode($data);

		return json_decode($response);
	}
} 