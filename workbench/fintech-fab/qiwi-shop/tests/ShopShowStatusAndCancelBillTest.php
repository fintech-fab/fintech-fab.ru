<?php


use FintechFab\QiwiShop\Models\Order;

class ShopShowStatusAndCancelBillTest extends ShopTestCase
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
	 * Счёт не найден
	 *
	 * @return void
	 */

	public function testShowStatusFailOrderNotFound()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/showStatus',
			array(
				'order_id' => '2',
			)
		);

		$this->assertContains('Нет такого заказа', $resp->original['message']);
	}

	/**
	 * Проверка счёта
	 *
	 * @return void
	 */

	public function testShowStatusPayable()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/showStatus',
			array(
				'order_id' => '1',
			)
		);

		$this->assertContains('Текущий статус счета - К оплате', $resp->original['message']);
	}

	/**
	 * Отмена счёта
	 */
	public function testCancelBill()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/cancelBill',
			array(
				'order_id' => '1',
			)
		);

		$this->assertContains('Счёт № 1 отменён.', $resp->original['message']);
	}


}