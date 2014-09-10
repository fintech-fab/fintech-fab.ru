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
	 * Incoming request validation rules.
	 * incoming names: 'terminal_id', 'event_sid', 'auth_sign', 'data'
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
	 * Event validators.
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
	 * Rules validators.
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

	/**
	 * Signal validators.
	 *
	 * @return array
	 */
	public static function getSignalValidator()
	{
		return [
			'name'        => 'required',
			'signal_sid'  => 'required|alpha_dash|unique:signals,signal_sid',
			'terminal_id' => 'integer',
		];
	}

	/**
	 * Terminal validators.
	 *
	 * @return array
	 */
	public static function getTerminalValidators()
	{
		return [
			'id'                    => 'integer|unique:terminals',
			'name'                  => 'required',
			'url'                   => 'url',
			'foreign_queue'         => '',
			'foreign_job'           => '',
			'key'                   => 'alpha_num',
			'password'              => 'required|confirmed',
			'password_confirmation' => 'required'
		];
	}

	/**
	 * Profile(terminal) validators.
	 *
	 * @return array
	 */
	public static function getProfileValidators()
	{
		return [
			'name'          => 'required',
			'url'           => 'url',
			'foreign_queue' => '',
			'foreign_job'   => '',
			'key'           => 'alpha_num',
		];
	}

	/**
	 * Profile(terminal) validators on password change.
	 *
	 * @return array
	 */
	public static function getProfileChangePassValidators()
	{
		return [
			'name'             => 'required',
			'url'              => 'url',
			'foreign_queue'    => '',
			'foreign_job'      => '',
			'key'              => 'alpha_num',
			'current_password' => 'required',
			'password'              => 'required|confirmed',
			'password_confirmation' => 'required'
		];
	}
} 