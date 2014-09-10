<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Rule
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @property int    $id
 * @property string $name
 * @property string $rule
 * @property bool   $flag_active
 * @property int    $terminal_id
 * @property int    $event_id
 * @property int    $signal_id
 * @property string created_at
 * @property string updated_at
 *
 * @method static Rule whereTerminalId()
 * @method static Rule whereEventId()
 * @method static Rule whereFlagActive()
 */
class Rule extends Eloquent
{

	/**
	 * @var string
	 */
	protected $table = 'rules';
	/**
	 * @var string
	 */
	protected $connection = 'ff-actions-calc';
	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'rule', 'flag_active', 'terminal_id', 'event_id', 'signal_id'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function signal()
	{
		return $this->belongsTo(Signal::class, 'signal_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function terminal()
	{
		return $this->belongsTo(Terminal::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function events() {
	    return $this->hasMany(Event::class, 'id', 'event_id');
	}
}