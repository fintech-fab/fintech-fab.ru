<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $terminal_id
 * @property string  $name
 * @property integer $event_id
 * @property string  $rule
 * @property integer $signal_id
 * @property boolean $flag_active
 * @property string  $updated_at
 * @property string  $created_at
 * @property Signal  $signal
 * @property Event   $event
 *
 * @method static Rule whereTerminalId()
 * @method static Rule whereEventId()
 * @method static Rule whereFlagActive()
 * @method static Rule links()
 */
class Rule extends Eloquent
{
	protected $table = 'rules';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('terminal_id', 'name', 'event_id', 'rule', 'signal_id', 'flag_active');

	public function terminal()
	{
		return $this->belongsTo(Terminal::class);
	}

	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	public function signal()
	{
		return $this->belongsTo(Signal::class);
	}

	/**
	 * Получить все правила по номеру терминала и событию
	 *
	 * @param integer $term
	 * @param string  $eventId
	 *
	 * @return array
	 */
	public static function getRules($term, $eventId)
	{
		return Rule::whereTerminalId($term)
			->whereEventId($eventId)
			->whereFlagActive(true)
			->get()->all();
	}

} 