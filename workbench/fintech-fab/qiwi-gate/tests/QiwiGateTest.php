<?php


class QiwiGateTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * @return void
	 */
	public function testCreateBillSuccess()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/4a5s6d',
			array(
				'user' => 'tel:+79161234567',
				'amount'  => '123.34',
				'ccy'     => 'RUB',
				'comment' => 'Test!'
			)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals('4a5s6d', $oResponse->response->bill->bill_id);
		$this->assertEquals('Test!', $oResponse->response->bill->comment);

	}


	/**
	 * @return Illuminate\Http\JsonResponse
	 */
	private function response()
	{
		return $this->client->getResponse();
	}

}