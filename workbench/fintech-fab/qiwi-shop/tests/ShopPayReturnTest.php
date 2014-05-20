<?php


use FintechFab\QiwiShop\Models\Order;
use FintechFab\QiwiShop\Models\PayReturn;

class ShopPayReturnTest extends ShopTestCase
{


	public function setUp()
	{
		parent::setUp();
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

	}

	/**
	 * При возврате сумма не указана
	 *
	 * @return void
	 */

	public function testPayReturnFailWithoutSum()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/payReturn',
			array(
				'order_id' => '1',
			)
		);
		$this->assertContains('Поле sum должно быть заполнено.', $resp->original['error']['sum']);
	}

	/**
	 * Возврат оплаты
	 *
	 * @return void
	 */

	public function testPayReturnSuccess()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/payReturn',
			array(
				'order_id' => '1',
				'sum'      => '15',
			)
		);
		$this->assertContains(
			'Сумма 15 руб. по счёту № 1 отправлена на возврат',
			$resp->original['message']
		);
	}

	/**
	 * Проверка статуса возврата с несозданным возвратом
	 */
	public function testShowStatusPayReturnFailReturnId()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/statusReturn',
			array(
				'order_id' => '1',
			)
		);
		$this->assertContains('Нет такого возврата', $resp->original['message']);
	}

	/**
	 * Проверка статуса возврата с несозданным возвратом
	 */
	public function testShowStatusPayReturn()
	{
		$order = Order::find(1);
		$order->idLastReturn = 1;
		$order->save();

		$payReturn = new PayReturn();
		$payReturn->create(array(
			'order_id' => $order->id,
			'sum'      => 15,
			'status'   => 'onReturn',
		));
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/statusReturn',
			array(
				'order_id' => '1',
			)
		);
		$this->assertContains(
			'Текущий статус возврата - на возврате',
			$resp->original['message']
		);
	}
}