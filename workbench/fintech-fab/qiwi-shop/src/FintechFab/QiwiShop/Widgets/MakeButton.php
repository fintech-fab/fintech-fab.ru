<?php
namespace FintechFab\QiwiShop\Widgets;

use Config;
use FintechFab\QiwiShop\Models\Order;
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
	/**
	 * @param Order $order
	 *
	 * @return array
	 */
	public static function displayTable($order)
	{
		$sumReturn = 0;
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
			case 'expired':
				$status = 'Просрочен';
				$activity = self::buttons('showStatus', $order->id);
				break;
			case 'paid':
				$status = 'Оплачен';
				$activity = self::buttons('showStatus', $order->id) . self::buttons('payReturn', $order->id);
				break;
			case 'returning':
				$status = 'Возврат оплаты';
				$activity = self::buttons('showStatus', $order->id) . self::buttons('statusReturn', $order->id);
				$statusReturn = $order->PayReturn()->find($order->idLastReturn)->status;

				$returnsBefore = $order->PayReturn;
				foreach ($returnsBefore as $one) {
					$sumReturn += $one->sum;
				}
				if ($sumReturn < $order->sum && $statusReturn == 'returned') {
					$activity .= self::buttons('payReturn', $order->id);
				}
				$sumReturn = number_format($sumReturn, 2, '.', ' ');
				switch ($statusReturn) {
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
				$query_data = array(
					'shop'        => Config::get('ff-qiwi-shop::GateAuth.merchant_id'),
					'transaction' => $order_id,
				);
				$button = link_to(url('qiwi/gate/order/external/main.action?' . http_build_query($query_data)),
					'Оплатить', array(
						'target' => '_blank',
						'id'     => 'payBill_' . $order_id,
						'class' => 'btn btn-success actionBtn pay',
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
					'class'       => 'btn btn-danger actionBtn return',
					'data-toggle' => 'modal',
					'data-target' => '#payReturn',
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