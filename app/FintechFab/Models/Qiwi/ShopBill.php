<?php

namespace FintechFab\Models\Qiwi;

use Eloquent;
use FintechFab\Models\User;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string  $user
 * @property string  $amount
 * @property string  $ccy
 * @property string  $comment
 * @property string  $lifetime
 * @property string  $pay_source*
 * @property string  $prv_name
 * @property string  $created_at
 * @property string  $updated_at
 */
class ShopBill extends Eloquent
{
	protected $fillable = array('user_id', 'user', 'amount', 'ccy', 'comment', 'lifetime', 'pay_source', 'prv_name');

	protected $table = 'users_bills';

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}