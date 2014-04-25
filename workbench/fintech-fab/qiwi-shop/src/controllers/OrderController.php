<?php

namespace FintechFab\QiwiShop\Controllers;


use View;

/**
 * Class OrderController
 *
 * @package FintechFab\QiwiShop\Controllers
 */
class OrderController extends BaseController
{

	public $layout = 'qiwiShop';

	/**
	 * @metod
	 */
	public function createOrder()
	{
		$this->layout->content = View::make('ff-qiwi-shop::qiwiShop.createOrder');
	}

	public function ordersTable()
	{
		$this->layout->content = View::make('ff-qiwi-shop::qiwiShop.ordersTable');
	}
}