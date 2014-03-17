<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.03.14
 * Time: 16:44
 */

namespace App\Controllers\Site;


use App\Controllers\BaseController;
use FintechFab\Models\User;

class UserProfileController extends BaseController
{
	// TODO
	public $layout = 'vanguard';

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
			);
		}

		//dd($x[0]);
		return $x;
	}
} 