<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class Role
 *
 * @package FintechFab\Models
 *
 * @property integer id
 * @property string role
 * @property string role_name
 * @property User[]  users
 *
 * @method Role whereRole static
 * @method Role first static
 * @method Role find($id) static
 */
class Role extends Eloquent
{
	protected $fillable = array('role', 'role_name');

	protected $table = 'roles';

	public function users()
	{
		return $this->belongsToMany('FintechFab\Models\User');
	}

}