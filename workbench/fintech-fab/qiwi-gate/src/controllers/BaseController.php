<?php

namespace FintechFab\QiwiGate\Controllers;

use Controller;
use Request;
use View;

class BaseController extends Controller
{

	private $sLayoutFolderName = 'default';

	protected function setupLayout()
	{
		if (!is_null($this->layout)) {
			$this->sLayoutFolderName = 'ff-qiwi-gate::' . $this->layout;
			$this->layout = View::make('ff-qiwi-gate::layouts.' . $this->layout);
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