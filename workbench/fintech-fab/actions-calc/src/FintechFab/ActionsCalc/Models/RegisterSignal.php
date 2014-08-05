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
	protected $connection = 'ff-actions-calc';
	protected $table = 'register_signals';
	protected $fillable = ['id', 'name', 'signal_id', 'signal_sid', 'terminal_id', 'flag_url', 'flag_queue'];
} 