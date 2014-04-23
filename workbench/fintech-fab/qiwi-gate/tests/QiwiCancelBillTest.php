<?php


use FintechFab\QiwiGate\Models\Bill;

class QiwiCancelBillTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();

		// создаём счёт
		Bill::truncate();
		$bill = new Bill;
		$bill->create(array(
			'merchant_id' => 1,
			'bill_id'     => '1q2w3e',
			'user'        => 'tel:+7123',
			'amount'      => 123.45,
			'ccy'         => 'RUB',
			'comment'     => 'test',
			'status'      => 'waiting',
		));
	}

	/**
	 * Запрошенный счёт не существует
	 *
	 * @return void
	 */
	public function testCancelBillFailBillId()
	{

		$this->call(
			'PATCH',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array('status' => 'rejected')
		);


		$oResponse = $this->response()->getData();
		$this->assertEquals(210, $oResponse->response->result_code);
		$this->assertEquals(404, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Успешная отмена счёта
	 *
	 * @return void
	 */
	public function testCancelBillSuccess()
	{

		$this->call(
			'PATCH',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/1q2w3e',
			array('status' => 'rejected')
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(0, $oResponse->response->result_code);
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertEquals('rejected', $oResponse->response->bill->status);
	}

	/**
	 * Омена невозможна, текущий статус счёта не waiting
	 *
	 * @return void
	 */
	public function testCancelBillFailStatus()
	{
		// создаём счёт со статусом paid
		Bill::truncate();
		$bill = new Bill;
		$bill->create(array(
			'merchant_id' => 1,
			'bill_id'     => '1q2w3e',
			'user'        => 'tel:+7123',
			'amount'      => 123.45,
			'ccy'         => 'RUB',
			'comment'     => 'test',
			'status'      => 'paid',
		));

		$this->call(
			'PATCH',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/1q2w3e',
			array('status' => 'rejected')
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(210, $oResponse->response->result_code);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * @return Illuminate\Http\JsonResponse
	 */
	private function response()
	{
		return $this->client->getResponse();
	}

}