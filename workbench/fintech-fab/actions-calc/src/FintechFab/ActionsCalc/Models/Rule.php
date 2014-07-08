<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $terminal_id
 * @property string  $name
 * @property string  $event_sid
 * @property string  $rule
 * @property string  $signal_sid
 * @property boolean $flag_active
 * @property string  $updated_at
 * @property string  $created_at
 *
 * @method static Rule whereTerminalId($term)
 * @method static Rule whereEventSid($sid)

 */
class Rule extends Eloquent
{
	protected $table = 'rules';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('terminal_id', 'name', 'event_sid', 'rule', 'signal_sid', 'flag_active');

	public function terminal()
	{
		return $this->belongsTo('FintechFab\ActionsCalc\Models\Terminal');
	}

	/**
	 * Получить все правила по номеру терминала и событию
	 *
	 * @param integer $term
	 * @param string  $sid
	 *
	 * @return array
	 */
	public static function getRules($term, $sid)
	{
		return Rule::whereTerminalId($term)->whereEventSid($sid)->get()->toArray();
	}

} 