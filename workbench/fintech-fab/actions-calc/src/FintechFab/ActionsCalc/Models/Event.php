<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Event
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @property int       $id
 * @property string    $event_sid
 * @property string    $name
 * @property string    $terminal_id
 * @property string    $data
 * @property string    $created_at
 * @property string    $updated_at
 *
 * @method static Event whereEventSid()
 * @method static Event whereTerminalId()
 */
class Event extends Eloquent
{
	/**
	 * @var string
	 */
	protected $table = 'events';
	/**
	 * @var string
	 */
	protected $connection = 'ff-actions-calc';
	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'event_sid', 'terminal_id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function terminal()
	{
		return $this->belongsTo(Terminal::class, 'terminal_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function rules() {
	    return $this->hasMany(Rule::class, 'event_id', 'id');
	}
}

 