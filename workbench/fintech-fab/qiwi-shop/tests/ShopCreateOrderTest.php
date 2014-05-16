<?php

use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Models\PayReturn;
use Illuminate\Auth\UserInterface;

class ShopCreateOrderTest extends ShopTestCase
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

		Order::truncate();

	}

	/**
	 * Неправильный формат данных
	 *
	 * @return void
	 */

	public function testCreateOrderFailTelFormat()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/create',
			array(
				'item' => 'Новая штука',
				'sum'  => '123',
				'tel'  => '123',
			)
		);
		$this->assertContains('Неправильный формат данных.', $resp->original['errors']['tel']);
	}

	/**
	 * Создание нового заказа
	 *
	 * @return void
	 */

	public function testCreateOrderSuccess()
	{
		PayReturn::truncate();

		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/create',
			array(
				'item'    => 'qwerty',
				'sum'     => '123',
				'tel'     => '+123',
				'comment' => 'test',
			)
		);

		$this->assertContains('ok', $resp->original['result']);
	}


}