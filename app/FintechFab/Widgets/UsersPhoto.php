<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.03.14
 * Time: 14:16
 */

namespace FintechFab\Widgets;

use Auth;

class UsersPhoto
{
	public static function getPhoto()
	{
		$user = Auth::user();
		$photo = '<figure class="photo">
					<img src="' . $user['photo'] . '" class="img">
					<div class="wrap">
						<div class="descr text-center">
							<a href=""> Загрузить фото</a>
						</div>
					</div>
				</figure>';

		return $photo;
	}

} 