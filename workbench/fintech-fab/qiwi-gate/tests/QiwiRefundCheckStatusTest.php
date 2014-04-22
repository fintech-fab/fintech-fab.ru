<?php


use FintechFab\QiwiGate\Models\Bill;
use FintechFab\QiwiGate\Models\Refund;

class QiwiRefundCheckStatusTest extends TestCase
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
			'status'      => 'paid',
		));

		// создаём возврат оплаты по счёту
		Refund::truncate();
		$refund = new Refund;
		$refund->create(array(
			'bill_id'   => 1,
			'refund_id' => '456',
			'amount'    => 123.45,
			'status'    => 'processing',
		));
	}

	/**
	 * Запрошенный счёт не существует
	 *
	 * @return void
	 */
	public function testCheckStatusFailBillNotIsset()
	{

		$this->call(
			'GET',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/4a5s6d/refund/456');


		$oResponse = $this->response()->getData();
		$this->assertEquals(210, $oResponse->response->result_code);
		$this->assertEquals(404, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Успешный запрос статуса счёта
	 *
	 * @return void
	 */
	public function testCheckStatusSuccess()
	{

		$this->call(
			'GET',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/1q2w3e/refund/456');


		$oResponse = $this->response()->getData();
		$this->assertEquals(0, $oResponse->response->result_code);
		$this->assertEquals('processing', $oResponse->response->bill->status);
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * @return Illuminate\Http\JsonResponse
	 */
	private function response()
	{
		return $this->client->getResponse();
	}

}