<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $event_id
 * @property string  $name
 * @property string  $signal_sid
 * @property string  $updated_at
 * @property string  $created_at
 */
class Signal extends Eloquent
{
	protected $table = 'signals';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('event_id', 'name', 'signal_sid');
} 