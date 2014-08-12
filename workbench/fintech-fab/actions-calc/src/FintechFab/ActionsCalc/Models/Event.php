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
 * @method static Event whereId()
 * @method static Event find()
 * @method static Event first()
 * @method static Event links()
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

	/**
	 * @return Rule
	 */
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

	public function changeEvent($data)
	{
		$this->name = $data['name'];
		$this->event_sid = $data['event_sid'];
		$this->save();
	}

	public function newEvent($data)
	{
		$this->name = $data['name'];
		$this->event_sid = $data['event_sid'];
		$this->terminal_id = $data['terminal_id'];
		$this->save();
	}

	public static function getEventSid()
	{
		$event = Event::select('id', 'name', 'event_sid')->get();
		$result = array();
		foreach ($event as $value) {
			$result[$value->id] = $value->event_sid . ' - (' . $value->name . ')';
		}

		return $result;
	}


}