<?php

use FintechFab\QiwiShop\Components\Sdk\Curl;
use FintechFab\QiwiShop\Components\Sdk\QiwiGateConnector;
use FintechFab\QiwiShop\Models\Order;

class ShopCreateBillTest extends ShopTestCase
{


	/**
	 * @var Mockery\MockInterface|Curl
	 */
	private $mock;

	public function setUp()
	{
		parent::setUp();
		$this->mock = Mockery::mock('FintechFab\QiwiShop\Components\Sdk\Curl');

	}

	/**
	 * Неправильный формат данных
	 *
	 * @return void
	 */

	public function testGetBillFailBillId()
	{
		Order::truncate();
		$order = new Order();
		$order->create(array(
			'user_id'  => 5,
			'item'     => 'New Lamp',
			'sum'      => 123.45,
			'tel'      => '+7123',
			'comment'  => 'test1231',
			'status'   => 'new',
			'lifetime' => date('Y-m-d H:i:s', time()),

		));
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/createBill',
			array(
				'order_id' => '10',
			)
		);

		$this->assertContains('Нет такого заказа', $resp->original['message']);
	}

	/**
	 * Получить новый счёт
	 *
	 * @return void
	 */

	public function testGetBillSuccess()
	{
		App::bind('FintechFab\QiwiShop\Components\Sdk\Curl', function () {

			$bill = array(
				'user'     => 'tel:+7123',
				'amount'   => 543.21,
				'ccy'      => 'RUB',
				'comment'  => 'without',
				'lifetime' => date('Y-m-d\TH:i:s', time() + 3600 * 24 * 3),
				'prv_name' => QiwiGateConnector::getConfig('provider.name'),
			);

			$args = array(
				1, 'PUT', $bill
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

			return $this->mock;
		});


		$order = new Order();
		$order->create(array(
			'user_id'  => 5,
			'item'     => 'New Lamp2',
			'sum'      => 543.21,
			'tel'      => '+7123',
			'comment'  => 'without',
			'status'   => 'new',
			'lifetime' => date('Y-m-d H:i:s', time() + 3600 * 24 * 3),
		));

		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/createBill',
			array(
				'order_id' => '1',
			)
		);
		$this->assertContains('Счёт выставлен', $resp->original['message']);
	}


}