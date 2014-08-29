<?php

namespace FintechFab\ActionsCalc\Components;

/**
 * Class Validators
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */

class Validators
{
	/**
	 * Incoming request validation rules
	 * 'terminal_id', 'event_sid', 'auth_sign', 'data'
	 *
	 * @return array
	 */
	public static function getRequestRules()
	{
		return [
			'terminal_id' => 'required|integer',
			'event_sid'   => 'required|alpha_dash',
			'data' => 'required',
		];
	}

	/**
	 * Event validators
	 *
	 * @return array
	 */
	public static function getEventRules()
	{
		return [
			'name'      => 'required',
			'event_sid' => 'required|alpha_dash|unique:events,event_sid',
		];
	}

	/**
	 * Rules validators
	 *
	 * @return array
	 */
	public static function getRuleValidators()
	{
		return [
			'name'      => 'required',
			'event_id'  => 'required|integer',
			'signal_id' => 'required|integer',
		];
	}
} 