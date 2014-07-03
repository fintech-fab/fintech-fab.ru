<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $terminal_id
 * @property string  $sid
 * @property string  $data
 * @property string  $updated_at
 * @property string  $created_at
 */
class Event extends Eloquent
{
	protected $table = 'events';

	protected $fillable = array('id', 'terminal_id', 'sid', 'data');
} 