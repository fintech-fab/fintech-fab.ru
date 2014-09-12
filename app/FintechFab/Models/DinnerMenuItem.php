<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class DinnerMenuItem
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string $title
 * @property string  $description
 * @property float  $price
 * @property string $date
 * @property integer $section_id
 * @property string  $deleted_at
 * @property string  $updated_at
 * @property string  $created_at
 */
class DinnerMenuItem extends Eloquent
{
	protected $fillable = array('title', 'description', 'price', 'date', 'section_id');

	protected $table = 'dinner_menu_items';

	public function section()
	{
		return $this->belongsTo('FintechFab\Models\DinnerMenuSection', 'section_id', 'id');
	}
}