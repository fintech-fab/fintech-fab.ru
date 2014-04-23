<?php


use FintechFab\QiwiGate\Models\Bill;
use FintechFab\QiwiGate\Models\Merchant;

class QiwiAuthTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();

		Route::enableFilters();

		// готовим мерчанта
		Merchant::truncate();
		$merchant = new Merchant;
		$merchant->create(array(
			'username' => 'username',
			'password' => 'password',
		));

	}

	/**
	 * @return void
	 */
	public function testCreateBillSuccess()
	{
		Bill::truncate();
		// запрос на создание счета, проверяем авторизацию с этим логином и паролем
		$this->client->request(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(
				'user'   => 'tel:+79161234567',
				'amount' => '123.34',
				'ccy'    => 'RUB',
			),
			array(),
			array(
				'HTTP_Authorization' => 'Basic ' . base64_encode('1:password'),
			)
		);

		$this->assertEquals(200, $this->client->getResponse()->getStatusCode());

	}

	/**
	 * @return void
	 */
	public function testCreateBillFail()
	{

		$this->client->request(
			'PUT',
			Config::get('ff-qiwi-gate::app.url') . '/qiwi/gate/api/v2/prv/1/bills/4a5s6d',
			array(),
			array(),
			array(
				'HTTP_Authorization' => 'Basic ' . base64_encode('321:bad-pass'),
			)
		);

		$this->assertEquals(401, $this->client->getResponse()->getStatusCode());


	}


}