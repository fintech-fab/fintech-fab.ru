<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.03.14
 * Time: 16:44
 */

namespace App\Controllers\User;


use App\Controllers\BaseController;
use Auth;
use FintechFab\Models\User;
use FintechFab\Widgets\UsersPhoto;

class UserProfileController extends BaseController
{

	public $layout = 'profile';

	public function showUserProfile()
	{
		return $this->make('userProfile');
	}

	public function showAdmin()
	{
		return $this->make('admin');
	}

	public function getPhoto()
	{
		return UsersPhoto::getPhoto();
	}

	public static function editUser($userData)
	{
		$user = User::find($userData['id']);
		$user->first_name = $userData['first_name'];
		$user->last_name = $userData['last_name'];
		$user->photo = $userData['photo'];
		$user->email = $userData['email'];
		$user->save();
		Auth::login($user);

		return $user;
	}

} 