<?php

namespace FintechFab\ActionsCalc\Models;

use Config;
use Eloquent;

/**
 * @property integer $id
 * @property integer $terminal_id
 * @property string  $name
 * @property string  $signal_sid
 * @property string  $updated_at
 * @property string  $created_at
 *
 * @method static Signal find()
 * @method static Signal first()
 * @method static Signal links()
 * @method static Signal whereId()
 * @method static Signal whereTerminalId()
 */
class Signal extends Eloquent
{
	protected $table = 'signals';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('terminal_id', 'name', 'signal_sid');

	public function terminal()
	{
		return $this->belongsTo(Terminal::class);
	}

	public function rules()
	{
		return $this->hasMany(Rule::class);
	}


	public function changeSignal($data)
	{
		$this->name = $data['name'];
		$this->signal_sid = $data['signal_sid'];
		$this->save();
	}

	public function newSignal($data)
	{
		$this->name = $data['name'];
		$this->signal_sid = $data['signal_sid'];
		$this->terminal_id = $data['terminal_id'];
		$this->save();
	}


	public static function getSignalSid()
	{
		$termId = Config::get('ff-actions-calc::termId');
		$signal = Signal::select('id', 'name', 'signal_sid')->where('terminal_id', '=', $termId)->get();
		$result = array();
		foreach ($signal as $value) {
			$result[$value->id] = $value->signal_sid . ' - (' . $value->name . ')';
		}

		return $result;
	}


}