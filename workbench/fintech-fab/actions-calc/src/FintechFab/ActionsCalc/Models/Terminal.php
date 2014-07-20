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

	public function event()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Event');
	}

	public function rule()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Rule');
	}

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