<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class RegisterSignal
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class RegisterSignal extends Eloquent
{
	/**
	 * @var string
	 */
	protected $connection = 'ff-actions-calc';
	/**
	 * @var string
	 */
	protected $table = 'register_signals';
	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'signal_id', 'signal_sid', 'terminal_id', 'flag_url', 'flag_queue'];
} 