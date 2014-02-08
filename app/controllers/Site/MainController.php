<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;

class MainController extends BaseController
{

	public $layout = 'site';

	public function index()
	{
		return $this->make('main');
	}

}