<?php

use FintechFab\QiwiShop\Components\Sdk\Curl;
use FintechFab\QiwiShop\Components\Sdk\QiwiGateConnector;

class ConnectorTest extends ShopTestCase
{

	/**
	 * @var Mockery\MockInterface|Curl
	 */
	private $mock;

	public function setUp()
	{
		parent::setUp();
		$this->mock = Mockery::mock('FintechFab\QiwiShop\Components\Sdk\Curl');
		$this->mock->shouldReceive('setUrl');
	}


	public function testCreateBill()
	{

		$connector = new QiwiGateConnector($this->mock);

		$bill = array(
			'user'     => 'tel:+7910000',
			'amount'   => 123.45,
			'ccy'      => 'RUB',
			'comment'  => 'SomeComment',
			'lifetime' => null,
			'prv_name' => QiwiGateConnector::getConfig('provider.name'),
		);

		$args = array(
			123, 'PUT', $bill
		);

		$this->mock
			->shouldReceive('request')
			->withArgs($args)
			->andReturn((object)array(
				'response' => (object)array(
						'result_code' => 0,
						'bill'        => (object)array(
								'bill_id' => 123,
							),
					)
			));

		$isSuccess = $connector->createBill(123, '+7910000', 123.45, 'SomeComment');
		$this->assertTrue($isSuccess);

	}

	public function testCreateBillFail()
	{

		$connector = new QiwiGateConnector($this->mock);

		$bill = array(
			'user'     => 'tel:+',
			'amount'   => 123.45,
			'ccy'      => 'RUB',
			'comment'  => null,
			'lifetime' => null,
			'prv_name' => QiwiGateConnector::getConfig('provider.name'),
		);

		$args = array(
			123, 'PUT', $bill
		);

		$this->mock
			->shouldReceive('request')
			->withArgs($args)
			->andReturn((object)array(
				'response' => (object)array(
						'result_code' => 5,
					)
			));

		$isSuccess = $connector->createBill(123, '+', 123.45);
		$this->assertFalse($isSuccess);
		$this->assertEquals('Неверный формат параметров запроса', $connector->getError());


	}


}