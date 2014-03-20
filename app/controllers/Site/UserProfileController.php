<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.03.14
 * Time: 16:44
 */

namespace App\Controllers\Site;


use App\Controllers\BaseController;
use FintechFab\Components\Role;
use FintechFab\Models\User;
use Input;

class UserProfileController extends BaseController
{
	// TODO
	public $layout = 'profile';

	public function showUserProfile()
	{
		return $this->make('userProfile');
	}

	public function showAdmin()
	{
		return $this->make('admin');
	}

	public function forAdmin()
	{
		$users = User::all();
		foreach ($users as $user) {
			$x[] = array(
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'admin'     => Role::userRole((int)$user->id, "admin"),
				'moderator' => Role::userRole((int)$user->id, "moderator"),
				'user'      => Role::userRole((int)$user->id, "user"),
			);
		}

		return $x;
	}

	public function changeRole()
	{
		$userN = Input::get('userN');
		$roleN = Input::get('roleN');
		$val = Input::get('val');
		$user = User::find($userN);
		//dd($val);
		if ($val == "true") {
			$user->roles()->attach($roleN);
		} else {
			$user->roles()->detach($roleN);
		}

	}
} 