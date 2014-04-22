<?php


use FintechFab\QiwiGate\Models\Bill;
use FintechFab\QiwiGate\Models\Refund;

class QiwiRefundBillTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();

		Refund::truncate();

		// создаём оплаченный счёт
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
	}

	/**
	 * Запрошенный счёт не существует
	 *
	 * @return void
	 */
	public function testRefundBillFailBillId()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/4a5s6d/refund/456',
			array('amount' => 50)
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
	public function testRefundBillSuccess()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/1q2w3e/refund/456',
			array('amount' => 50)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(0, $oResponse->response->result_code);
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
		$this->assertEquals('processing', $oResponse->response->refund->status);
	}

	/**
	 * Сумма слишком мала
	 *
	 * @return void
	 */
	public function testRefundBillFailSmallAmount()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/1q2w3e/refund/456',
			array('amount' => 5)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(241, $oResponse->response->result_code);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Сумма слишком велика
	 *
	 * @return void
	 */
	public function testRefundBillFailBigAmount()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/1q2w3e/refund/456',
			array('amount' => 9000)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(242, $oResponse->response->result_code);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Омена невозможна, текущий статус счёта не waiting
	 *
	 * @return void
	 */
	public function testRefundBillFailStatus()
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
			'status'      => 'waiting',
		));

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/1q2w3e/refund/456',
			array('amount' => 50)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals('Wrong status', $oResponse->response->result_code);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * @return Illuminate\Http\JsonResponse
	 */
	public function testRefundBillFailIdExist()
	{
		// создаём возврат оплаты по счёту
		Refund::truncate();
		$refund = new Refund;
		$refund->create(array(
			'bill_id'   => '1q2w3e',
			'refund_id' => '456',
			'amount'    => 123.45,
			'status'    => 'processing',
		));

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/123/bills/1q2w3e/refund/456',
			array('amount' => 50)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(5, $oResponse->response->result_code);
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