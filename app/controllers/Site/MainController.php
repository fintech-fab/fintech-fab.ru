<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;

class MainController extends BaseController
{

	public $layout = 'main';

	public function index()
	{
		return $this->make('index');
	}

}