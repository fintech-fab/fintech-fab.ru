<?php

namespace FintechFab\QiwiGate\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $bill_id
 * @property integer refund_id
 * @property string  $amount
 * @property string  $error
 * @property string  $status
 * @property string  $user
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @method static Merchant find()
 */
class Refund extends Eloquent
{
	protected $fillable = array(
		'bill_id', 'refund_id', 'amount', 'status', 'user',
	);
	protected $table = 'bills_refund';
	protected $connection = 'qiwiGate';

	public function bill()
	{
		return $this->belongsTo('\FintechFab\QiwiGate\Models\Bill');
	}
}