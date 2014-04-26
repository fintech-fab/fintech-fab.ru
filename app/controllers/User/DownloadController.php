<?php

namespace App\Controllers\User;


use App\Controllers\BaseController;
use Auth;
use Input;

class DownloadController extends BaseController
{
	public function uploadImage()
	{
		$mime = $mime = Input::file('image')->getMimeType();
		$mime = explode('/', $mime);
		if ($mime[0] != 'image') {
			return false;
		}
		$ext = $mime[1];
		$user_id = Auth::user()->getAuthIdentifier();
		$filePath = 'img/userPhoto/' . $user_id . '/avatar';
		$name = Input::file('image')->getClientOriginalName();
		$name = explode('.', $name);
		array_pop($name);
		$name = implode('.', $name);
		$fileName = $name . '.' . $ext;
		Input::file('image')->move($filePath, $fileName);
		$userData = Auth::user();
		$userData['photo'] = $filePath . '/' . $fileName;
		$user = UserProfileController::editUser($userData);
		$user['password'] = '';

		return $user;

	}
} 