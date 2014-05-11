<?php

namespace FintechFab\QiwiGate\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $bill_id
 * @property integer $refund_id
 * @property string  $amount
 * @property string  $status
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property Bill    $bill
 *
 * @method static Refund find()
 * @method static Refund where()
 * @method static Refund first()
 *
 * @method static Refund whereBillId($id)
 * @method static Refund whereRefundId($id)
 * @method static Refund whereStatus($status)
 */
class Refund extends Eloquent
{
	protected $fillable = array(
		'bill_id', 'refund_id', 'amount', 'status',
	);
	protected $table = 'bills_refund';
	protected $connection = 'qiwiGate';

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Bill
	 */
	public function bill()
	{
		return $this->belongsTo('\FintechFab\QiwiGate\Models\Bill');
	}
}