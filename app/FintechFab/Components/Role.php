<?php

namespace FintechFab\Components;


use FintechFab\Models\User;

class Role
{
	public static function userRole($id, $role_name)
	{
		$result = 0;
		$user = User::find($id);
		foreach ($user->roles as $role) {
			if ($role_name == $role->role_name) {
				$result = "checked";
			}
		}

		return $result;
	}
} 