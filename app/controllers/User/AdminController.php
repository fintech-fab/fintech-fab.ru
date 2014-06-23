<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
//use FintechFab\Components\Role; // Удалить файл. Он нигде больше не используется, и есть другой класс с тем же названием, в каталоге Models.
use FintechFab\Models\User;
//use FintechFab\Models\Role;
use Input;
use DB;

/**
 * Class AdminController
 *
 * @package App\Controllers\User
 *
 */
class AdminController extends BaseController
{
	public $layout = 'profile';

	/**
	 *
	 * @return mixed
	 */
	public function userRoles()
	{
		$userTable = array();

		$users = User::orderBy('last_name')->orderBy('first_name')->paginate(10);
		foreach($users as $user)
		{
			$roles = $user->roles;
			$userTable[] = array(
				'id'         => $user->id,
				'first_name' => $user->first_name,
				'last_name'  => $user->last_name,
				'admin'      => isset($roles->find(1)->id) ? 'checked' : '',
				'moderator'  => isset($roles->find(2)->id) ? 'checked' : '',
				'user'       => isset($roles->find(3)->id) ? 'checked' : '',
				'messageSender'         => isset($roles->find(4)->id) ? 'checked' : '',
				'messageSubscriber'     => isset($roles->find(5)->id) ? 'checked' : ''
			);
		}

		return $this->make('admin', array('userTable' => $userTable, 'pageLinks' => $users->links()) );
	}

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
		SELECT u.id, u.first_name, u.last_name,
			max( IF( r.id = 1, 'checked', 0 ) ) AS admin,
			max( IF( r.id = 2, 'checked', 0 ) ) AS moderator,
			max( IF( r.id = 3, 'checked', 0 ) ) AS `user`,
			max( IF( r.id = 4, 'checked', 0 ) ) AS messageSender,
			max( IF( r.id = 5, 'checked', 0 ) ) AS messageSubscriber
		FROM " . DB::getTablePrefix() . "roles AS r
			RIGHT OUTER JOIN " . DB::getTablePrefix() . "role_user AS ru ON r.id = ru.role_id
			RIGHT OUTER JOIN " . DB::getTablePrefix() . "users AS u ON ru.user_id = u.id
		GROUP BY u.id, u.last_name, u.first_name
		ORDER BY u.last_name, u.first_name
		LIMIT ? , ?";

		$users = DB::select($sql, array( ( ($curPage-1)*$rowsOnPage), $rowsOnPage));


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