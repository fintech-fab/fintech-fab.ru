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

	public function contact()
	{
		return $this->make('contact');
	}

	public function about()
	{
		return $this->make('about');
	}

	public function projects()
	{
		return $this->make('projects');
	}

	public function mobile2care()
	{
		return $this->make('m2c');
	}

	public function wlp()
	{
		return $this->make('wlp');
	}

	public function anyany()
	{
		return $this->make('anyany');
	}

	public function replies()
	{
		return $this->make('replies');
	}


}