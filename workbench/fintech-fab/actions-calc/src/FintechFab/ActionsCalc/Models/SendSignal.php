<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $event_id
 * @property string  $signal_sid
 * @property boolean $flag_url
 * @property boolean $flag_queue
 * @property string  $updated_at
 * @property string  $created_at
 */
class SendSignal extends Eloquent
{
	protected $table = 'signals';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('event_id', 'signal_sid', 'flag_url', 'flag_queue');

	public function events()
	{
		return $this->belongsTo('FintechFab\ActionsCalc\Models\Event');
	}
} 