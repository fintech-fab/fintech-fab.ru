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
 *
 * @method static Signal find()
 */
class Signal extends Eloquent
{
	protected $table = 'signals';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('event_id', 'signal_sid', 'flag_url', 'flag_queue');

	/**
	 * @return Event
	 */
	public function event()
	{
		return $this->belongsTo('FintechFab\ActionsCalc\Models\Event');
	}

	public function setFlagUrlTrue()
	{
		$this->flag_url = true;
		$this->save();
	}

	public function setFlagQueueTrue()
	{
		$this->flag_queue = true;
		$this->save();
	}

	public function newSignal($eventId, $signalSid)
	{
		$this->event_id = $eventId;
		$this->signal_sid = $signalSid;
		$this->save();
	}
} 