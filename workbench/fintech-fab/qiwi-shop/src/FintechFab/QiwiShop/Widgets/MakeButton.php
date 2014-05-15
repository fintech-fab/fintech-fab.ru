<?php
namespace FintechFab\QiwiShop\Widgets;

use Config;
use FintechFab\QiwiShop\Components\Dictionary;
use FintechFab\QiwiShop\Models\Order;
use Form;
use URL;


/**
 * Class MakeTable
 *
 * @package FintechFab\QiwiShop\Widgets
 *
 * @return array
 */
class MakeButton
{
	/**
	 * @param Order $order
	 *
	 * @return array
	 */
	public static function displayTable($order)
	{
		$sumReturn = 0;
		$status = Dictionary::statusRussian($order->status);
		switch ($order->status) {
			case Order::C_ORDER_STATUS_NEW:
				$activity = self::buttons('showStatus', $order->id) . self::buttons('createBill', $order->id);
				break;
			case Order::C_ORDER_STATUS_PAYABLE:
				$activity = self::buttons('showStatus', $order->id) . self::buttons('payBill', $order->id) .
					self::buttons('cancelBill', $order->id);
				break;
			case Order::C_ORDER_STATUS_CANCELED:
				$activity = self::buttons('showStatus', $order->id);
				break;
			case Order::C_ORDER_STATUS_EXPIRED:
				$activity = self::buttons('showStatus', $order->id);
				break;
			case Order::C_ORDER_STATUS_PAID:
				$activity = self::buttons('showStatus', $order->id) . self::buttons('payReturn', $order->id);
				break;
			case Order::C_ORDER_STATUS_RETURNING:
				$activity = self::buttons('showStatus', $order->id) . self::buttons('statusReturn', $order->id);
				$statusReturn = $order->PayReturn->find($order->idLastReturn)->status;

				//Вычисляем сумму для
				$returnsBefore = $order->PayReturn;
				foreach ($returnsBefore as $one) {
					$sumReturn += $one->sum;
				}
				if (
					$sumReturn < $order->sum &&
					$statusReturn == Order::C_RETURN_STATUS_RETURNED
				) {
					$activity .= self::buttons('payReturn', $order->id);
				}
				$sumReturn = number_format($sumReturn, 2, '.', ' ');
				$status .= ' / ' . Dictionary::statusRussian($statusReturn);

				break;
			default:
				$status = 'Ошибка';
				$activity = 'Ошибка статуса';
		}

		return array('status' => $status, 'activity' => $activity, 'sumReturn' => $sumReturn);
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
					'class'       => 'btn btn-info tableBtn',
					'data-action' => 'showStatus',
					'data-id'     => $order_id,
				));
				break;
			case 'createBill':
				$button = Form::button('Выставить счёт', array(
					'class'       => 'btn btn-primary tableBtn',
					'data-action' => 'createBill',
					'data-id'     => $order_id,
				));
				break;
			case 'payBill':
				$query_data = array(
					'shop'        => Config::get('ff-qiwi-shop::provider.id'),
					'transaction' => $order_id,
				);
				$button = link_to(url(URL::route('payIndex') . '?' . http_build_query($query_data)),
					'Оплатить', array(
						'target' => '_blank',
						'class'  => 'btn btn-success margin-likeTableBtn',
					));
				break;
			case 'cancelBill':
				$button = Form::button('Отменить', array(
					'class'       => 'btn btn-warning tableBtn',
					'data-action' => 'cancelBill',
					'data-id'     => $order_id,
				));
				break;
			case 'payReturn':
				$button = Form::button('Возврат отплаты', array(
					'class'       => 'btn btn-danger actionBtn',
					'data-toggle' => 'modal',
					'data-target' => '#payReturn',
					'data-action' => 'payReturn',
					'data-id'     => $order_id,
				));
				break;
			case 'statusReturn':
				$button = Form::button('Статус возврата', array(
					'class'       => 'btn btn-primary tableBtn',
					'data-action' => 'statusReturn',
					'data-id'     => $order_id,
				));
				break;
			default:
				$button = 'Неизвестный статус';

		}

		return $button;
	}

} 