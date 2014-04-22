<?php

namespace FintechFab\QiwiGate\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $merchant_id
 * @property integer $bill_id
 * @property string  $user
 * @property string  $amount
 * @property string  $ccy
 * @property string  $comment
 * @property string  $lifetime
 * @property string  $pay_source*
 * @property string  $prv_name
 * @property string  $status
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @method static Merchant find()
 */
class Bill extends Eloquent
{
	protected $fillable = array(
		'merchant_id', 'bill_id', 'user', 'amount', 'ccy', 'comment', 'lifetime',
		'pay_source', 'prv_name', 'status'
	);
	protected $table = 'merchants_bills';
	protected $connection = 'qiwiGate';

	public function merchant()
	{
		return $this->belongsTo('\FintechFab\QiwiGate\Models\Merchant');
	}

	public function refunds()
	{
		return $this->hasMany('FintechFab\QiwiGate\Models\Refund');
	}
}