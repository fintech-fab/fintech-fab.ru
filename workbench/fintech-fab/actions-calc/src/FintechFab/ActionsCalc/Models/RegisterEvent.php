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
	/**
	 * @var string
	 */
	protected $connection = 'ff-actions-calc';
	/**
	 * @var string
	 */
	protected $table = 'register_events';
	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'event_id', 'event_sid', 'terminal_id', 'data'];


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function terminal()
	{
		return $this->belongsTo(Terminal::class, 'terminal_id');
	}
}