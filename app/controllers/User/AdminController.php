<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
//use FintechFab\Components\Role; // Удалить файл. Он нигде больше не используется, и есть другой класс с тем же названием, в каталоге Models.
use FintechFab\Models\User;
use Input;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminController
 *
 * @package App\Controllers\User
 *
 * @method select
 */
class AdminController extends BaseController
{

	/**
	 *
	 * @return mixed
	 */
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

		/*$x = null;
		$count = User::all()->count();
		if (!$count) {
			$x = "В Базе данных еще нет пользователей";
		} else {
			//$users = User::all();
			foreach ($users as $user) {
				$x[] = array(
					'id'         => $user->id,
					'first_name' => $user->first_name,
					'last_name'  => $user->last_name,
					'admin'      => Role::userRole((int)$user->id, "admin"),
					'moderator'  => Role::userRole((int)$user->id, "moderator"),
					'user'       => Role::userRole((int)$user->id, "user"),
				);
			}
		}*/
		//$y['userRoles'] = $x;

		$countRows = count($users);
		$y['pageNum'] = $curPage;
		$y['pageMax'] = intval(ceil($countRows/$rowsOnPage));
		if ($countRows == 0) {
			$y['userRoles'] = "В Базе данных еще нет пользователей";
		} else	{
			$y['userRoles'] = $users;
		}

		return $y;
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