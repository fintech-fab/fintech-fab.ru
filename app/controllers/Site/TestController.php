<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;

class TestController extends BaseController
{

	public $layout = 'test';

	public function index()
	{
		return $this->make('test');
	}

}