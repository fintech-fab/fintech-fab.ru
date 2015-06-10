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

	public function en()
	{
		return $this->make('en');
	}

	public function contact()
	{
		return $this->make('index');
	}

	public function about()
	{
		return $this->make('index');
	}

	public function projects()
	{
		return $this->make('index');
	}

	public function mobile2care()
	{
		return $this->make('index');
	}

	public function wlp()
	{
		return $this->make('wlp');
	}

	public function anyany()
	{
		return $this->make('anyany');
	}


}