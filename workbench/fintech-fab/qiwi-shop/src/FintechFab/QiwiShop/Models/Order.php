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
 *
 *
 */
class Order extends Eloquent
{

	protected $fillable = array(
		'user_id', 'item', 'sum', 'tel', 'comment', 'lifetime', 'status'
	);
	protected $table = 'orders';
	protected $connection = 'qiwiShop';

} 