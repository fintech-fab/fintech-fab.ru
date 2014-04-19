<?php

namespace FintechFab\Models;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Collection;

/**
 * @property integer                    $id
 * @property string                     $first_name
 * @property string                     $last_name
 * @property string                     $email
 * @property string                     $password
 * @property string                     $updated_at
 * @property string                     $created_at
 *
 * @property Collection|SocialNetwork[] SocialNetworks
 * @method User find() static
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{
	/**
	 * Ardent validation rules
	 */
	public static $rules = array(
		'first_name'     => 'required',
		'email'          => 'required|email|unique:users',
		'password'       => 'required|min:4|alpha_dash',
		'passwordRepeat' => 'same:password',
	);

	/**
	 * Factory
	 */
	public static $factory = array(
		'first_name' => 'string',
		'email'      => 'email',
		'password'   => 'password',
	);


	protected $fillable = array('first_name', 'last_name', 'email');

	public function SocialNetworks()
	{
		return $this->hasMany(SocialNetwork::class);

	}

	public function roles()
	{
		return $this->belongsToMany(Role::class);
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

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		// TODO: Implement getRememberToken() method.
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 *
	 * @return void
	 */
	public function setRememberToken($value)
	{
		// TODO: Implement setRememberToken() method.
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		// TODO: Implement getRememberTokenName() method.
	}
}