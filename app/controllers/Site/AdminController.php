<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Components\Role;
use FintechFab\Models\User;
use Input;

class AdminController extends BaseController
{

	//public $layout = 'vanguard';

	public function TableForAdmin()
	{
		$count = User::all()->count();
		if (!$count) {
			$x = "В Базе данных еще нет пользователей";
		} else {
			$users = User::all();
			foreach ($users as $user) {
				$x[] = array(
					'first_name' => $user->first_name,
					'last_name'  => $user->last_name,
					'admin'      => Role::userRole((int)$user->id, "admin"),
					'moderator'  => Role::userRole((int)$user->id, "moderator"),
					'user'       => Role::userRole((int)$user->id, "user"),
				);
			}
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

		$res = "Изменения произошли для пользователя с порядковым номером  $userN";

		return $res;
	}
} 