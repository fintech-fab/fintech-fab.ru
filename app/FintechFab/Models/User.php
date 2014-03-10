<?php

namespace FintechFab\Models;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;

/**
 * @property integer $id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $email
 * @property string  $password
 * @property string  $updated_at
 * @property string  $created_at
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{

	protected $fillable = array('first_name', 'last_name', 'email');

	public function SocialNetworks()
	{
		return $this->has_many('SocialNetwork');
	}

	protected $table = 'users';

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->id;
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}
}