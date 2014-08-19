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
 */
class Signal extends Eloquent
{
	protected $connection = 'ff-actions-calc';
	protected $table = 'signals';
	protected $fillable = ['id', 'name', 'terminal_id', 'signal_sid'];

	public function terminal()
	{
		$this->belongsTo('Terminal', 'terminal_id');
	}
}