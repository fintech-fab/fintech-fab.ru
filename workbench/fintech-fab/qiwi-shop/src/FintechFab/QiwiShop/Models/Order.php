<?php

namespace FintechFab\QiwiShop\Models;

use Eloquent;

/**
 * Class Order
 *
 * @package FintechFab\QiwiShop\Models
 *
 * @property integer   $user_id
 * @property integer   $id
 * @property string    $item
 * @property string    $sum
 * @property string    $tel
 * @property string    $comment
 * @property string    $lifetime
 * @property string    $status
 * @property integer   $idLastReturn
 * @property PayReturn $PayReturn
 *
 * @method static Order whereUserId()
 * @method static Order whereStatus()
 * @method static Order orWhereStatus()
 * @method static Order whereId()
 * @method static Order find()
 * @method static Order links()
 */
class Order extends Eloquent
{

	const C_ORDER_STATUS_NEW = 'new';
	const C_ORDER_STATUS_PAYABLE = 'payable';
	const C_ORDER_STATUS_PAID = 'paid';
	const C_ORDER_STATUS_RETURNING = 'returning';
	const C_ORDER_STATUS_CANCELED = 'canceled';
	const C_ORDER_STATUS_EXPIRED = 'expired';
	const C_RETURN_STATUS_ON_RETURN = 'onReturn';
	const C_RETURN_STATUS_RETURNED = 'returned';

	protected $fillable = array(
		'user_id', 'item', 'sum', 'tel', 'comment', 'lifetime', 'status', 'idLastReturn'
	);
	protected $table = 'orders';
	protected $connection = 'ff-qiwi-shop';

	/**
	 * @return PayReturn
	 */
	public function PayReturn()
	{
		return $this->hasMany('FintechFab\QiwiShop\Models\PayReturn');
	}

	/**
	 * Смена статуса заказа
	 *
	 * @param $newStatus
	 */
	public function changeStatus($newStatus)
	{
		//Не меняем статус с "на возврате" на статус "оплачено"
		if ($this->status == self::C_ORDER_STATUS_RETURNING &&
			$newStatus == self::C_ORDER_STATUS_PAID
		) {
			return;
		}

		if ($this->status != $newStatus) {

			Order::whereId($this->id)
				->whereStatus($this->status)
				->update(array('status' => $newStatus));

		}

		$order = Order::find($this->id);
		$this->status = $order->status;

	}

	public function isNew()
	{
		return $this->status == self::C_ORDER_STATUS_NEW;
	}

	/**
	 * Закончен ли придыдущий возврат?
	 *
	 * @return bool
	 */
	public function isOnReturn()
	{
		if ($this->idLastReturn != null) {
			$currentStatusReturn = $this->PayReturn()->find($this->idLastReturn)->status;

			return $currentStatusReturn == 'onReturn';
		}

		return false;
	}

	/**
	 * Изменяем id последнего возврата
	 *
	 * @param $IdPayReturn
	 */
	public function changeAfterReturn($IdPayReturn)
	{
		Order::whereId($this->id)
			->update(array(
				'status'       => 'returning',
				'idLastReturn' => $IdPayReturn
			));
	}
} 