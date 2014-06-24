<?php

namespace FintechFab\Models\Qiwi;

use Eloquent;

/**
 * @property integer $id
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
class QiwiBil extends Eloquent
{
	protected $fillable = array('user', 'amount', 'ccy', 'comment', 'lifetime', 'pay_source', 'prv_name');

	protected $table = 'qiwi_bills';

}