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
		$userTable = array();

		$users = User::orderBy('last_name')->orderBy('first_name')->paginate(25);
		$id1 = Role::whereRole('admin')->first()->id;
		$id2 = Role::whereRole('moderator')->first()->id;
		$id3 = Role::whereRole('user')->first()->id;
		$id4 = Role::whereRole('messageSender')->first()->id;
		$id5 = Role::whereRole('messageSubscriber')->first()->id;
		foreach($users as $user)
		{
			$roles = $user->roles;
			$userTable[] = array(
				'id'         => $user->id,
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'admin'      => isset($roles->find($id1)->id) ? 'checked' : '',
				'moderator'  => isset($roles->find($id2)->id) ? 'checked' : '',
				'user'       => isset($roles->find($id3)->id) ? 'checked' : '',
				'messageSender'         => isset($roles->find($id4)->id) ? 'checked' : '',
				'messageSubscriber'     => isset($roles->find($id5)->id) ? 'checked' : ''
			);
		}

		return $this->make('userRoles', array('userTable' => $userTable, 'pageLinks' => $users->links()) );
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