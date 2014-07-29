<?php

namespace App\Controllers\Site;

use App\Controllers\BaseController;
//use FintechFab\Components\Helper;
use Input;
//use Redirect;

class DevelopNewsController extends BaseController
{

	public $layout = 'developNews';

	public function developNews()
	{
		$inTime = 1;
		if(input::has('inTime'))
		{
			$inTime = input::get('inTime');
		}
		return $this->make('developNews', array('inTime' => $inTime));
	}

}