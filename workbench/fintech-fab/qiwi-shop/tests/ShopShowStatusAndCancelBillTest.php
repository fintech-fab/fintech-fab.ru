<?php

use Illuminate\Auth\UserInterface;

class ShopShowStatusAndCancelBillTest extends TestCase
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
	 * Счёт не найден
	 *
	 * @return void
	 */

	public function testShowStatusBillNotFound()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/showStatus',
			array(
				'order_id' => '1',
			)
		);

		$this->assertContains('Счет не найден', $resp->original['message']);
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
				'order_id' => '2',
			)
		);

		$this->assertContains('Текущий статус счета - К оплате', $resp->original['message']);
	}

	public function testCancelBillSuccess()
	{
		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/cancelBill',
			array(
				'order_id' => '2',
			)
		);

		$this->assertContains('Счёт № 2 отменён.', $resp->original['message']);
	}


}