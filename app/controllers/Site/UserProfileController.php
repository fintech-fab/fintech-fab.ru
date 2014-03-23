<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.03.14
 * Time: 16:44
 */

namespace App\Controllers\Site;


use App\Controllers\BaseController;

class UserProfileController extends BaseController
{
	// TODO
	public $layout = 'profile';

	public function showUserProfile()
	{
		return $this->make('userProfile');
	}

	public function showAdmin()
	{
		return $this->make('admin');
	}

} 