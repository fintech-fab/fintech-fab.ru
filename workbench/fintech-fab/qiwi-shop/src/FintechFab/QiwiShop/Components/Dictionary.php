<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.05.14
 * Time: 16:57
 */

namespace FintechFab\QiwiShop\Components;


use FintechFab\QiwiShop\Models\Order;

class Dictionary
{

	public static function statusRussian($status)
	{
		$statusRussian = array(
			Order::C_ORDER_STATUS_NEW        => 'Новый',
			Order::C_ORDER_STATUS_PAYABLE    => 'К оплате',
			Order::C_ORDER_STATUS_CANCELED   => 'Отменён',
			Order::C_ORDER_STATUS_EXPIRED    => 'Просрочен',
			Order::C_ORDER_STATUS_PAID       => 'Оплачен',
			Order::C_ORDER_STATUS_RETURNING  => 'Возврат оплаты',
			Order::C_RETURN_STATUS_ON_RETURN => 'на возврате',
			Order::C_RETURN_STATUS_RETURNED  => 'возвращен',
		);

		return $statusRussian[$status];
	}

} 