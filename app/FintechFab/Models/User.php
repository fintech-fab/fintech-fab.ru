<?php

namespace FintechFab\Models;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\DB;

/**
 * @property integer         $id
 * @property string          $first_name
 * @property string          $last_name
 * @property string          $email
 * @property string          $password
 * @property string          $updated_at
 * @property string          $created_at
 * @property string          $remember_token
 *
 * @property Role            $roles
 *
 * @method User find() static
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{

	protected $fillable = array('first_name', 'last_name', 'email');

	/**
	 * @return SocialNetwork
	 */
	public function SocialNetworks()
	{
		return $this->hasMany('FintechFab\Models\SocialNetwork');

	}

	public function roles()
	{
		return $this->belongsToMany('FintechFab\Models\Role');
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
		return $this->remember_token;
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
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

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

	/**
	 * The check of roles
	 *
	 * @param  string $role
	 *
	 * @return bool
	 */
	public function isCompetent($role)
	{
		$sql =
			"SELECT count( * ) AS cnt
			FROM roles INNER JOIN
				role_user ON roles.id = role_user.role_id
			WHERE (role_user.user_id = ?) AND (roles.role ='Admin' OR roles.role = ?)";
		$res = DB::select($sql, array($this->id, $role))[0];

		return ($res->cnt > 0);
	}
}