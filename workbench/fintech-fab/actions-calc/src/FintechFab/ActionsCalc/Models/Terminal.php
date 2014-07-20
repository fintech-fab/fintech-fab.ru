<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $name
 * @property string  $url
 * @property string  $queue
 * @property string  $key
 * @property string  $password
 * @property string  $updated_at
 * @property string  $created_at
 *
 * @method   static Terminal find()
 */
class Terminal extends Eloquent
{
	protected $table = 'terminals';
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('name', 'url', 'queue', 'password', 'key',);

	/**
	 * @return IncomeEvent
	 */
	public function incomeEvent()
	{
		return $this->hasMany(IncomeEvent::class);
	}

	/**
	 * @return Event
	 */
	public function event()
	{
		return $this->hasMany(Event::class);
	}

	/**
	 * @return Signal
	 */
	public function signal()
	{
		return $this->hasMany(Signal::class);
	}

	/**
	 * @return Rule
	 */
	public function rules()
	{
		return $this->hasMany(Rule::class);
	}

	/**Создание нового пользователя
	 *
	 * @param $data
	 */
	public function newTerminal($data)
	{
		if (!$data['key']) {
			$data['key'] = md5($data['username'] . $data['termId'] . $data['queue']);
		}
		$this->id = $data['termId'];
		$this->name = $data['username'];
		$this->url = $data['url'];
		$this->queue = $data['queue'];
		$this->key = $data['key'];
		$this->password = $data['password'];
		$this->save();
	}

	/**Изменение данных пользователя
	 *
	 * @param $data
	 */
	public function changeTerminal($data)
	{
		$this->name = $data['username'];
		$this->url = $data['url'];
		$this->queue = $data['queue'];
		$this->key = $data['key'];
		$this->password = $data['password'];
		$this->save();
	}

} 