<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class RegisterEvent
 *
 * @property int       $id
 * @property string    $event_sid
 * @property string    $name
 * @property string    $terminal_id
 * @property string    $data
 * @property string    $created_at
 * @property string    $updated_at
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 */
class RegisterEvent extends Eloquent
{
	protected $connection = 'ff-actions-calc';
	protected $table = 'register_events';
	protected $fillable = ['id', 'name', 'event_sid', 'terminal_id'];


	public function terminal()
	{
		return $this->belongsTo(Terminal::class, 'terminal_id');
	}
}