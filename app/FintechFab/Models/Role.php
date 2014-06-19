<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class Role
 *
 * @package FintechFab\Models
 *
 * @property integer id
 *
 * @method Role whereRole static
 * @method Role first static
 */
class Role extends Eloquent
{
	protected $fillable = array('role', 'role_name');

	protected $table = 'roles';

	public function users()
	{
		//return $this->belongsToMany(User::class);
		return $this->belongsToMany('FintechFab\Models\User');
	}

}