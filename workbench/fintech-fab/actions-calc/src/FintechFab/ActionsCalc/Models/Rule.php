<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $terminal_id
 * @property string  $name
 * @property string  $event_sid
 * @property string  $rule
 * @property string  $signal
 * @property boolean $flag_active
 * @property string  $updated_at
 * @property string  $created_at
 */
class Rule extends Eloquent
{
	protected $table = 'rules';

	protected $fillable = array('terminal_id', 'name', 'event_sid', 'rule', 'signal', 'flag_active');

	public function terminals()
	{
		return $this->belongsTo('FintechFab\ActionsCalc\Models\Terminal');
	}
} 