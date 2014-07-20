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
 */
class Event extends Eloquent
{
	protected $table = 'rules';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('terminal_id', 'name', 'event_sid');

	public function terminal()
	{
		return $this->belongsTo(Terminal::class);
	}

	/**
	 * Получить все события по номеру терминала и событию
	 *
	 * @param integer $term
	 * @param string  $event
	 *
	 * @return array
	 */
	public static function getEvents($term, $event)
	{
		return Rule::whereTerminalId($term)->whereEventSid($event)->get()->all();
	}

} 