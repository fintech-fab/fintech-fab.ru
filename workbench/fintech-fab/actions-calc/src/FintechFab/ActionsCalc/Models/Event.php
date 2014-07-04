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

	protected $fillable = array('terminal_id', 'sid', 'data');

	public function terminals()
	{
		return $this->belongsTo('FintechFab\ActionsCalc\Models\Terminal');
	}

	public function sendSignals()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\SendSignal');
	}
} 