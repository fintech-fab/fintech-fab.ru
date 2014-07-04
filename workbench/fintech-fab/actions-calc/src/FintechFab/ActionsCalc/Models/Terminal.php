<?php

namespace FintechFab\ActionsCalc\Models;

use Eloquent;

/**
 * @property integer $id
 * @property integer $name
 * @property string  $url
 * @property string  $queue
 * @property string  $updated_at
 * @property string  $created_at
 */
class Terminal extends Eloquent
{
	protected $table = 'terminals';

	protected $fillable = array('name', 'url', 'queue');

	public function events()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Event');
	}

	public function rules()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Rule');
	}


} 