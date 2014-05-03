<?php

namespace FintechFab\QiwiShop\Models;

use Eloquent;

/**
 * Class Order
 *
 * @package FintechFab\QiwiShop\Models
 *
 * @property integer $user_id
 * @property integer $id
 * @property string  $item
 * @property string  $sum
 * @property string  $tel
 * @property string  $comment
 * @property string  $lifetime
 * @property string  $status
 * @property integer $idLastReturn
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

	protected $fillable = array(
		'user_id', 'item', 'sum', 'tel', 'comment', 'lifetime', 'status', 'idLastReturn'
	);
	protected $table = 'orders';
	protected $connection = 'qiwiShop';

	/**
	 * @return PayReturn
	 */
	public function PayReturn()
	{
		return $this->hasMany('FintechFab\QiwiShop\Models\PayReturn');
	}
} 