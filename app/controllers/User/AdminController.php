<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use FintechFab\Components\Role;
use FintechFab\Models\User;
use Input;
use Illuminate\Support\Facades\DB;

class AdminController extends BaseController
{

	public function TableForRoles()
	{
		$curPage = 1;
		if(isset($_GET['pageNum'])){
			$curPage = $_GET['pageNum'];
		}
		$rowsOnPage = 25;
		$sql = "
		SELECT users.id, users.first_name, users.last_name,
			max( IF( roles.id = 1, 'checked', 0 ) ) AS admin,
			max( IF( roles.id = 2, 'checked', 0 ) ) AS moderator,
			max( IF( roles.id = 3, 'checked', 0 ) ) AS `user`,
			max( IF( roles.id = 4, 'checked', 0 ) ) AS messageSender,
			max( IF( roles.id = 5, 'checked', 0 ) ) AS messageSubscriber
		FROM roles
			RIGHT OUTER JOIN role_user ON roles.id = role_user.role_id
			RIGHT OUTER JOIN users ON role_user.user_id = users.id
		GROUP BY users.id, users.first_name, users.last_name
		ORDER BY users.first_name, users.last_name
		LIMIT ? , ?";

		$users = DB::select($sql, array( ( ($curPage-1)*$rowsOnPage), $rowsOnPage));

		$x = null;
		//if (!$count) {
		if (count($users) == 0) {
			$x = "В Базе данных еще нет пользователей";
		} else {
			//$users = User::all();
			foreach ($users as $user) {
				$x[] = array(
					'id'         => $user->id,
					'first_name' => $user->first_name,
					'last_name'  => $user->last_name,
					//'admin'      => Role::userRole((int)$user->id, "admin"),
					//'moderator'  => Role::userRole((int)$user->id, "moderator"),
					//'user'       => Role::userRole((int)$user->id, "user"),
					'admin'      => $user->admin,
					'moderator'  => $user->moderator,
					'user'       => $user->user,
					'messageSender'       => $user->messageSender,
					'messageSubscriber'   => $user->messageSubscriber,
				);
			}
		}
		$y['pageNum'] = $curPage;
		$count = User::all()->count();
		$y['pageMax'] = intval(ceil($count/$rowsOnPage));
		$y['userRoles'] = $x;

		return $y;
	}

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