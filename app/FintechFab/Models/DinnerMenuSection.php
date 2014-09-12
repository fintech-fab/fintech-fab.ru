<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class DinnerMenuSection
 *
 * @package FintechFab\Models
 *
 * @property integer $id
 * @property string  $title
 * @property string  $deleted_at
 * @property string  $updated_at
 * @property string  $created_at
 */
class DinnerMenuSection extends Eloquent
{
	protected $fillable = array('title');

	protected $table = 'dinner_menu_sections';
}