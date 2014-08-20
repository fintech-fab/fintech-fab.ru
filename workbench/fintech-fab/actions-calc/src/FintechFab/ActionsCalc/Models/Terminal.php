<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * Class Terminal
 *
 * @author Ulashev Roman <truetamtam@gmail.com>
 *
 * @property int    $id
 * @property string $name
 * @property string $url
 * @property string $foreign_queue
 * @property string $foreign_job
 * @property string $key
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 */
class Terminal extends Eloquent
{
	protected $connection = 'ff-actions-calc';
	protected $table = 'terminals';
	protected $fillable = ['id', 'name', 'url', 'foreign_queue', 'foreign_job', 'key', 'password'];

	/**
	 * Accessor for paginating events
	 *
	 * @return mixed
	 */
	public function getEventsAttribute() {
	    return $this->events()->paginate(2);
	}

	public function rules()
	{
		return $this->hasMany(Rule::class, 'terminal_id', 'id');
	}

	public function events()
	{
		return $this->hasMany(Event::class, 'terminal_id', 'id');
	}

	public function signals()
	{
		return $this->hasMany(Signal::class, 'terminal_id', 'id');
	}
}

 