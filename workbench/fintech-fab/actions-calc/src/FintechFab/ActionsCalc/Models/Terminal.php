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
	/**
	 * @var string
	 */
	protected $connection = 'ff-actions-calc';
	/**
	 * @var string
	 */
	protected $table = 'terminals';

	/**
	 * @var array
	 */
	protected $fillable = ['id', 'name', 'url', 'foreign_queue', 'foreign_job', 'key'];
	/**
	 * @var array
	 */
	protected $guarded = ['password'];

	/**
	 * Accessor for paginating events on manage page.
	 *
	 * @return mixed
	 */
	public function getEventsAttribute()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->events()->paginate(10);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function rules()
	{
		return $this->hasMany(Rule::class, 'terminal_id', 'id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function events()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->hasMany(Event::class, 'terminal_id', 'id')->orderBy('created_at', 'desc');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function signals()
	{
		/** @noinspection PhpUndefinedMethodInspection */
		return $this->hasMany(Signal::class, 'terminal_id', 'id')->orderBy('created_at', 'desc');
	}
}

 