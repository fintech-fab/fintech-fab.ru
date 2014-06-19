<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * Class RoleUser
 *
 * @package FintechFab\Models
 *
 * @method RoleUser whereRoleId static
 * @method RoleUser whereUserId static
 */
class RoleUser extends Eloquent
{
	//protected $fillable = array('user_id', 'role_id');
	public $timestamps = false;

	protected $table = 'role_user';



}