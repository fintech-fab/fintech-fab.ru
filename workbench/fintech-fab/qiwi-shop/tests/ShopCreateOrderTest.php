<?php

use FintechFab\QiwiShop\Models\PayReturn;

class ShopCreateOrderTest extends ShopTestCase
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