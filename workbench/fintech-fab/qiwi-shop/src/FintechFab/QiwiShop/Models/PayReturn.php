<?php

namespace FintechFab\QiwiShop\Models;

use Eloquent;

/**
 * Class PayReturn
 *
 * @package FintechFab\QiwiShop\Models
 *
 * @property integer $id
 * @property integer $order_id
 * @property string  $sum
 * @property string  $comment
 * @property string  $status
 *
 * @method static PayReturn whereOrderId()
 * @method static PayReturn whereStatus()
 * @method static PayReturn whereId()
 * @method static PayReturn find()
 */
class PayReturn extends Eloquent
{

	protected $fillable = array(
		'order_id', 'sum', 'comment', 'status'
	);
	protected $table = 'orders_pay_return';
	protected $connection = 'ff-qiwi-shop';

	/**
	 * @return Order
	 */
	public function merchant()
	{
		return $this->belongsTo('\FintechFab\QiwiShop\Models\Order');
	}

	public function changeStatus($newReturnStatus)
	{
		if ($this->status != $newReturnStatus) {
			$isUpdate = PayReturn::whereId($this->id)
				->whereStatus($this->status)
				->update(array('status' => $newReturnStatus));

			if ($isUpdate) {
				return $newReturnStatus;
			}
		}

		return $this->status;
	}

} 