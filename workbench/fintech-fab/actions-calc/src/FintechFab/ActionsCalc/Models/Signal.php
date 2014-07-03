<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $event_id
 * @property string  $name
 * @property string  $signal_sid
 * @property boolean $flag_url
 * @property boolean $flag_queue
 * @property string  $updated_at
 * @property string  $created_at
 */
class Signal extends Eloquent
{
	protected $table = 'signals';

	protected $fillable = array('id', 'event_id', 'name', 'signal_sid', 'flag_url', 'flag_queue');
} 