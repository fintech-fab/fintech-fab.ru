<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $terminal_id
 * @property string  $name
 * @property string  $event_sid
 * @property string  $updated_at
 * @property string  $created_at
 *
 * @method static Event whereTerminalId()
 * @method static Event whereEventSid()
 *
 */
class Event extends Eloquent
{
	protected $table = 'events';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('terminal_id', 'name', 'event_sid');

	public function terminal()
	{
		return $this->belongsTo(Terminal::class);
	}

	public function rules()
	{
		return $this->hasMany(Rule::class);
	}

	/**
	 * Получить событие по номеру терминала и коду события
	 *
	 * @param integer $termId
	 * @param string  $eventSid
	 *
	 * @return Event
	 */
	public static function getEvent($termId, $eventSid)
	{
		return Event::whereTerminalId($termId)->whereEventSid($eventSid)->first();
	}

} 