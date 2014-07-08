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
	protected $connection = 'ff-actions-calc';

	protected $fillable = array('name', 'url', 'queue');

	public function event()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Event');
	}

	public function rule()
	{
		return $this->hasMany('FintechFab\ActionsCalc\Models\Rule');
	}


} 