<?php

use FintechFab\QiwiShop\Models\Order;
use Illuminate\Auth\UserInterface;

class ShopGetBillTest extends ShopTestCase
{


	public function setUp()
	{
		parent::setUp();

		/**
		 * @var UserInterface|Mockery\MockInterface $mock
		 */
		$mock = Mockery::mock('Illuminate\Auth\UserInterface');
		$mock->shouldReceive('getAuthIdentifier')->andReturn(5);
		Auth::login($mock);

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
				'order_id' => '2',
			)
		);
		$this->assertContains('Счёт № 2 выставлен', $resp->original['message']);
	}


}