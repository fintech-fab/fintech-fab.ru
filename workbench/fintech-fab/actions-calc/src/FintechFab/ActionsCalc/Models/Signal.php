<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Signal
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @property int    $id
 * @property string $name
 * @property string $signal_sid
 * @property int    $terminal_id
 * @property bool   $flag_url
 * @property bool   $flag_queue
 * @property string $created_at
 * @property string $updated_at
 *
 * @method static Signal whereSignalId()
 * @method static Signal whereTerminalId()
 */
class Signal extends Eloquent
{
	/**
	 * @var string
	 */
	protected $connection = 'ff-actions-calc';
	/**
	 * @var string
	 */
	protected $table = 'signals';
	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'terminal_id', 'signal_sid'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function terminal() // TODO: events for certain terminal.
	{
		return $this->belongsTo('Terminal', 'terminal_id');
	}
}