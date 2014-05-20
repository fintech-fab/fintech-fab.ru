<?php

use FintechFab\QiwiShop\Models\Order;
use Illuminate\Auth\UserInterface;

class ShopTestCase extends TestCase
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

}