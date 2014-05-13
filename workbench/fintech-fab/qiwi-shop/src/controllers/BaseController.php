<?php

namespace FintechFab\QiwiShop\Controllers;

use Controller;
use Request;
use View;

class BaseController extends Controller
{

	private $sLayoutFolderName = 'default';

	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->sLayoutFolderName = 'ff-qiwi-shop::' . $this->layout;
			$this->layout = View::make('ff-qiwi-shop::layouts.' . $this->layout);
		}
	}

	protected function make($sTemplate, $aParams = array())
	{
		if (Request::ajax()) {
			return $this->makePartial($sTemplate, $aParams);
		} else {
			return $this->layout->nest('content', $this->sLayoutFolderName . '.' . $sTemplate, $aParams);
		}
	}

	protected function makePartial($sTemplate, $aParams = array())
	{
		return View::make($this->sLayoutFolderName . '.' . $sTemplate, $aParams);
	}


}