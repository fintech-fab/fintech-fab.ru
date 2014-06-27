<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
use FintechFab\Models\User;
use FintechFab\Models\Role;
use Input;

/**
 * Class AdminController
 *
 * @package App\Controllers\User
 *
 */
class AdminController extends BaseController
{
	public $layout = 'admin';

	/**
	 *
	 * @return mixed
	 */
	public function userRoles()
	{
		$users = User::orderBy('last_name')->orderBy('first_name')->paginate(25);

		$allRoles = Role::all();
		$rolesStr = array();
		foreach($allRoles as $role)
		{
			$list = explode(" ", $role->role_name);
			$rolesStr[$role->role] = $list[0];
		}
		$userTableHead = $rolesStr;

		$userTable = array();
		foreach($users as $user)
		{
			$roles = $user->roles;
			foreach($allRoles as $role)
			{
				$rolesStr[$role->role] = isset($roles->find($role->id)->id) ? 'checked' : '';
			}
			$userTable[] = array(
				'id'         => $user->id,
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'roles'      => $rolesStr
			);
		}

		return $this->make('userRoles', array(
			'userTable' => $userTable,
			'userTableHead' => $userTableHead,
			'pageLinks' => $users->links())
		);
	}



	/**
	 * @return string
	 */
	public function changeRole()
	{
		$userN = Input::get('userN');
		$roleN = Input::get('roleN');
		$val = Input::get('val');
		$user = User::find($userN);

		if ($val == "true") {
			$user->roles()->attach($roleN);
		} else {
			$user->roles()->detach($roleN);
		}

		$res = "Изменения произошли для пользователя с порядковым номером  $userN";

		return $res;
	}


} 