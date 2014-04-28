<?php

use FintechFab\QiwiShop\Models\Order;

class ShopCreateOrderTest extends TestCase
{


	public function setUp()
	{
		parent::setUp();


		$this->call(
			'POST',
			'/auth',
			array(
				'email'    => 'vasya@example.com',
				'password' => '123123',
			)
		);
	}

	/**
	 * Неправильный формат данных
	 *
	 * @return void
	 */

	public function testCreateOrderFailFormat()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl'),
			array(
				'item' => 'qwerty',
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
		Order::truncate();

		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl'),
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