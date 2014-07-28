<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Event
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 * @property int    $id
 * @property int    $event_id
 * @property string $name
 * @property string $terminal_id
 * @property string $data
 * @property string $created_at
 * @property string $updated_at
 */
class Event extends Eloquent
{
	protected $table = 'events';
	protected $connection = 'ff-actions-calc';

	public function terminal()
	{
		return $this->belongsTo(Terminal::class, 'terminal_id');
	}
}

 