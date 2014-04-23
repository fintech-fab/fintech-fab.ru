<?php


use FintechFab\QiwiGate\Models\Bill;

class QiwiCreateBillTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();
	}

	/**
	 * Неправильный формат данных
	 *
	 * @return void
	 */

	public function testCreateBillFailFormat()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(

				'amount'  => '123.34',
				'ccy'     => 'RUB',
				'comment' => 'Test!'
			)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(5, $oResponse->response->result_code);
		$this->assertEquals(400, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Большая сумма
	 *
	 * @return void
	 */
	public function testCreateBillFailBigAmount()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(
				'user'    => 'tel:+79161234567',
				'amount'  => '60000',
				'ccy'     => 'RUB',
				'comment' => 'Test!'
			)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(242, $oResponse->response->result_code);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	/**
	 * Маленькая сумма
	 *
	 * @return void
	 */
	public function testCreateBillFailSmallAmount()
	{

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(
				'user'    => 'tel:+79161234567',
				'amount'  => '3',
				'ccy'     => 'RUB',
				'comment' => 'Test!'
			)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(241, $oResponse->response->result_code);
		$this->assertEquals(403, $this->client->getResponse()->getStatusCode());
	}

	/**
	 *
	 * @return void
	 */
	public function testCreateBillSuccess()
	{
		Route::enableFilters();
		Bill::truncate();

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(
				'user'    => 'tel:+79161234567',
				'amount'  => '123.34',
				'ccy'     => 'RUB',
				'comment' => 'Test!'
			),
			array(),
			array(
				'HTTP_Authorization' => 'Basic ' . base64_encode('1:password'),
			)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals('4a5s6d', $oResponse->response->bill->bill_id);
		$this->assertEquals('Test!', $oResponse->response->bill->comment);
		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());
	}

	/**
	 *
	 * @return void
	 */
	public function testCreateBillFailBillIdIsset()
	{
		Route::enableFilters();

		$this->call(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(
				'user'    => 'tel:+79161234567',
				'amount'  => '123.34',
				'ccy'     => 'RUB',
				'comment' => 'Test!'
			),
			array(),
			array(
				'HTTP_Authorization' => 'Basic ' . base64_encode('1:password'),
			)
		);

		$oResponse = $this->response()->getData();
		$this->assertEquals(215, $oResponse->response->bill->error);
		$this->assertEquals(215, $oResponse->response->result_code);
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