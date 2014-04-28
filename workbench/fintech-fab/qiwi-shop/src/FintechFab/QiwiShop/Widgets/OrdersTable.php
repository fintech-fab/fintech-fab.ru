<?php
namespace FintechFab\QiwiShop\Widgets;


use FintechFab\QiwiShop\Models\Order;
use Form;

/**
 * Class OrdersTable
 *
 * @package FintechFab\QiwiShop\Widgets
 */
class OrdersTable
{


	public static function displayTable($user_id)
	{
		$orders = Order::whereUserId($user_id)->paginate(10);

		$table = '<table class="table table-striped table-hover" id="ordersTable">
				<tr><td><b>№</b></td><td><b>ID</b></td><td><b>Название</b></td>
				<td><b>Сумма</b></td><td><b>Статус</b></td>
				<td><b>Телефон</b></td><td><b>Действия с заказом</b></td></tr>';
		$i = 1;
		foreach ($orders as $order) {
			switch ($order->status) {
				case 'new':
					$status = 'Новый';
					$activity = self::buttons('status', $order->id) . self::buttons('getBill', $order->id);
					break;
				case 'payable':
					$status = 'К оплате';
					$activity = self::buttons('status', $order->id) . self::buttons('pay', $order->id) .
						self::buttons('cancel', $order->id);
					break;
				case 'canceled':
					$status = 'Отменён';
					$activity = self::buttons('status', $order->id);
					break;
				case 'paid':
					$status = 'Оплачен';
					$activity = self::buttons('status', $order->id) . self::buttons('return', $order->id) .
						self::buttons('cancel', $order->id);
					break;
				case 'returned':
					$status = 'Возврат оплаты';
					$activity = self::buttons('status', $order->id) . self::buttons('statusReturn', $order->id);
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
					$status = '';
					$activity = '';
			}

			$table .= '<tr><td>' . $i . '</td><td>' . $order->id . '</td><td>' . $order->item . '</td>
				<td>' . $order->sum . '</td><td>' . $status . '</td>
				<td>' . $order->tel . '</td><td>' . $activity . '</td></tr>';
			$i++;
		}

		$table .= '</table>' . $orders->links();

		return $table;
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
			case 'status':
				$button = Form::button('Статус счёта', array(
					'id'    => 'status' . $order_id,
					'class' => 'btn btn-info tableBtn',
				));
				break;
			case 'getBill':
				$button = Form::button('Выставить счёт', array(
					'id'    => 'getBill' . $order_id,
					'class' => 'btn btn-primary tableBtn',
				));
				break;
			case 'pay':
				$button = Form::button('Оплатить', array(
					'id'    => 'pay' . $order_id,
					'class' => 'btn btn-success  tableBtn',
				));
				break;
			case 'cancel':
				$button = Form::button('Отменить', array(
					'id'    => 'cancel' . $order_id,
					'class' => 'btn btn-warning tableBtn',
				));
				break;
			case 'return':
				$button = Form::button('Возврат отплаты', array(
					'id'    => 'return' . $order_id,
					'class' => 'btn btn-danger tableBtn',
				));
				break;
			case 'statusReturn':
				$button = Form::button('Статус возврата', array(
					'id'    => 'returnStatus' . $order_id,
					'class' => 'btn btn-primary tableBtn',
				));
				break;
			default:
				$button = 'Неизвестный статус';

		}

		return $button;
	}

} 