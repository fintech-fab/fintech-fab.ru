<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;

class TestController extends BaseController
{

	public $layout = 'site';

	public function index()
	{
		return $this->make('main');
	}


}