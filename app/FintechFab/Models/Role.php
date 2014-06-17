<?php

namespace FintechFab\Models;

use Eloquent;

class Role extends Eloquent
{
	protected $fillable = array('role', 'role_name');

	protected $table = 'roles';

	public function users()
	{
		return $this->belongsToMany('FintechFab\Models\User');
	}

}