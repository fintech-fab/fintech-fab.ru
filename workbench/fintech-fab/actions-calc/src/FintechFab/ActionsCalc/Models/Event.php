<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer  $id
 * @property integer  $terminal_id
 * @property string   $sid
 * @property string   $data
 * @property string   $updated_at
 * @property string   $created_at
 * @property Terminal $terminal
 */
class Event extends Eloquent
{
	protected $table = 'events';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('terminal_id', 'sid', 'data');

	/**
	 * @return Terminal
	 */
	public function terminal()
	{
		return $this->belongsTo('FintechFab\ActionsCalc\Models\Terminal');
	}

	public function signal()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Signal');
	}

	public function newEvent($termId, $sid, $data)
	{
		$dataString = urldecode(http_build_query($data, null, ' | '));

		$this->terminal_id = $termId;
		$this->sid = $sid;
		$this->data = $dataString;
		$this->save();
	}
} 