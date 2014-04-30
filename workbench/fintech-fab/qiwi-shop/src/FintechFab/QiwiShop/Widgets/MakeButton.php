<?php
namespace FintechFab\QiwiShop\Widgets;

use Form;

/**
 * Class MakeTable
 *
 * @package FintechFab\QiwiShop\Widgets
 *
 * @return array
 */
class MakeButton
{
	public static function displayTable($order)
	{
		switch ($order->status) {
			case 'new':
				$status = 'Новый';
				$activity = self::buttons('showStatus', $order->id) . self::buttons('createBill', $order->id);
				break;
			case 'payable':
				$status = 'К оплате';
				$activity = self::buttons('showStatus', $order->id) . self::buttons('payBill', $order->id) .
					self::buttons('cancelBill', $order->id);
				break;
			case 'canceled':
				$status = 'Отменён';
				$activity = self::buttons('showStatus', $order->id);
				break;
			case 'paid':
				$status = 'Оплачен';
				$activity = self::buttons('showStatus', $order->id) . self::buttons('payReturn', $order->id);
				break;
			case 'returned':
				$status = 'Возврат оплаты';
				$activity = self::buttons('showStatus', $order->id) . self::buttons('statusReturn', $order->id);
				switch ($order->statusReturn) {
					case 'onReturn':
						$status .= ' / на возврате';
						break;
					case 'returned':
						$status .= ' / возвращен';
						break;
				}
				break;
			default:
				$status = 'Ошибка';
				$activity = 'Ошибка статуса';
		}

		return array('status' => $status, 'activity' => $activity);
	}

	/**
	 * @param $type
	 * @param $order_id
	 *
	 * @return string
	 */
	private static function buttons($type, $order_id)
	{
		switch ($type) {
			case 'showStatus':
				$button = Form::button('Статус счёта', array(
					'id'    => 'showStatus_' . $order_id,
					'class' => 'btn btn-info tableBtn status',
				));
				break;
			case 'createBill':
				$button = Form::button('Выставить счёт', array(
					'id'    => 'createBill_' . $order_id,
					'class' => 'btn btn-primary tableBtn getBill',
				));
				break;
			case 'payBill':
				$button = Form::button('Оплатить', array(
					'id'    => 'payBill_' . $order_id,
					'class' => 'btn btn-success tableBtn pay',
				));
				break;
			case 'cancelBill':
				$button = Form::button('Отменить', array(
					'id'    => 'cancelBill_' . $order_id,
					'class' => 'btn btn-warning tableBtn cancel',
				));
				break;
			case 'payReturn':
				$button = Form::button('Возврат отплаты', array(
					'id'    => 'payReturn_' . $order_id,
					'class' => 'btn btn-danger tableBtn return',
				));
				break;
			case 'statusReturn':
				$button = Form::button('Статус возврата', array(
					'id'    => 'statusReturn_' . $order_id,
					'class' => 'btn btn-primary tableBtn statusReturn',
				));
				break;
			default:
				$button = 'Неизвестный статус';

		}

		return $button;
	}

} 