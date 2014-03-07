<?php

namespace FintechFab\Models;

use Eloquent;

/**
 * @property integer $id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $email
 * @property string  $password
 * @property string  $updated_at
 * @property string  $created_at
 */
class User extends Eloquent
{

	protected $fillable = array('first_name', 'last_name', 'email');

	protected $table = 'users';

	public function getName()
	{
		return trim($this->first_name . ' ' . $this->last_name);
	}

}