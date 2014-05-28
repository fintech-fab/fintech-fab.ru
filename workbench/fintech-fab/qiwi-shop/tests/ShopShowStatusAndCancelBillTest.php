<?php


use FintechFab\QiwiSdk\Curl;
use FintechFab\QiwiShop\Models\Order;

class ShopShowStatusAndCancelBillTest extends ShopTestCase
{
	/**
	 * @var Mockery\MockInterface|Curl
	 */
	private $mock;

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
		$this->mock = Mockery::mock('FintechFab\QiwiSdk\Curl');

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
		App::bind('FintechFab\QiwiSdk\Curl', function () {

			$args = array(1);

			$this->mock
				->shouldReceive('request')
				->withArgs($args)
				->andReturn((object)array(
					'response' => (object)array(
							'result_code' => 0,
							'bill'        => (object)array(
									'status' => 'waiting',
								),
						)
				));

			return $this->mock;
		});

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
		App::bind('FintechFab\QiwiSdk\Curl', function () {

			$reject = array('status' => 'rejected');
			$args = array(
				1, 'PATCH', $reject
			);

			$this->mock
				->shouldReceive('request')
				->withArgs($args)
				->andReturn((object)array(
					'response' => (object)array(
							'result_code' => 0,
						)
				));

			return $this->mock;
		});

		$resp = $this->call(
			'POST',
			Config::get('ff-qiwi-shop::testConfig.testUrl') . '/cancelBill',
			array(
				'order_id' => '1',
			)
		);

		$this->assertContains('Счёт отменён.', $resp->original['message']);
	}


}