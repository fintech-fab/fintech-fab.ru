<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class DinnerMenuItems
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string $title
 * @property string  $description
 * @property float  $price
 * @property string $date
 * @property string  $deleted_at
 * @property string  $updated_at
 * @property string  $created_at
 */
class DinnerMenuItem extends Eloquent
{
	protected $fillable = array('title', 'description', 'price', 'date');

	protected $table = 'dinner_menu_items';
}